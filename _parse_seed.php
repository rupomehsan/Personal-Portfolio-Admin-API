<?php
/**
 * Parse the SQL dump and emit two PHP-array fragments used by the seeders:
 *   _seed_categories.php.frag
 *   _seed_blogs.php.frag
 *
 * Source tables:
 *   - `categories`            (rows with category_type containing "blog")
 *   - `blogs`
 *   - `blog_post_categories`  (pivot — first match decides blog_category_id)
 */

ini_set('pcre.backtrack_limit', '100000000');
ini_set('pcre.recursion_limit', '100000000');

$path = __DIR__ . '/modernit_my_personal_bd (1).sql';
$sql  = file_get_contents($path);

/** Tokenize a single `(...)` MySQL row tuple. */
function parse_tuple(string $s): array {
    $vals = [];
    $n = strlen($s);
    if ($n < 2 || $s[0] !== '(' || $s[$n - 1] !== ')') return [];
    $i = 1; $end = $n - 1;
    while ($i < $end) {
        while ($i < $end && ctype_space($s[$i])) $i++;
        if ($i >= $end) break;
        $ch = $s[$i];
        if ($ch === "'") {
            $i++; $buf = '';
            while ($i < $end) {
                $c = $s[$i];
                if ($c === '\\' && $i + 1 < $end) {
                    $nxt = $s[$i + 1];
                    $map = ['n' => "\n", 't' => "\t", 'r' => "\r", '0' => "\0", '\\' => '\\', "'" => "'", '"' => '"'];
                    $buf .= $map[$nxt] ?? $nxt;
                    $i += 2; continue;
                }
                if ($c === "'") {
                    if ($i + 1 < $end && $s[$i + 1] === "'") { $buf .= "'"; $i += 2; continue; }
                    $i++; break;
                }
                $buf .= $c; $i++;
            }
            $vals[] = $buf;
        } elseif ($ch === 'N' && substr($s, $i, 4) === 'NULL') {
            $vals[] = null; $i += 4;
        } else {
            $j = $i;
            while ($j < $end && $s[$j] !== ',') $j++;
            $tok = trim(substr($s, $i, $j - $i));
            if ($tok !== '') {
                if (is_numeric($tok)) {
                    $vals[] = (strpos($tok, '.') !== false) ? (float)$tok : (int)$tok;
                } else {
                    $vals[] = $tok;
                }
            }
            $i = $j;
        }
        while ($i < $end && ctype_space($s[$i])) $i++;
        if ($i < $end && $s[$i] === ',') $i++;
    }
    return $vals;
}

/** Return list of row tuples for every INSERT INTO `$table` ... statement. */
function extract_inserts(string $sql, string $table): array {
    $rows = [];
    $re = '/INSERT INTO `' . preg_quote($table, '/') . '`\s*\([^)]*\)\s*VALUES\s*(.*?);\s*$/sm';
    if (!preg_match_all($re, $sql, $m)) return $rows;
    foreach ($m[1] as $body) {
        $i = 0; $n = strlen($body);
        while ($i < $n) {
            while ($i < $n && (ctype_space($body[$i]) || $body[$i] === ',')) $i++;
            if ($i >= $n || $body[$i] !== '(') break;
            $j = $i + 1; $depth = 1;
            while ($j < $n && $depth > 0) {
                $c = $body[$j];
                if ($c === "'") {
                    $j++;
                    while ($j < $n) {
                        $cc = $body[$j];
                        if ($cc === '\\') { $j += 2; continue; }
                        if ($cc === "'") {
                            if ($j + 1 < $n && $body[$j + 1] === "'") { $j += 2; continue; }
                            $j++; break;
                        }
                        $j++;
                    }
                    continue;
                }
                if ($c === '(') $depth++;
                elseif ($c === ')') { $depth--; if ($depth === 0) { $j++; break; } }
                $j++;
            }
            $rows[] = parse_tuple(substr($body, $i, $j - $i));
            $i = $j;
        }
    }
    return $rows;
}

/** Emit a PHP value literal (single-quoted strings, null, ints, floats). */
function php_value($v): string {
    if ($v === null) return 'null';
    if (is_bool($v)) return $v ? 'true' : 'false';
    if (is_int($v)) return (string)$v;
    if (is_float($v)) return var_export($v, true);
    $s = (string)$v;
    $s = str_replace('\\', '\\\\', $s);
    $s = str_replace("'", "\\'", $s);
    return "'" . $s . "'";
}

function php_array_rows(array $rows, string $indent = '            '): string {
    $out = [];
    foreach ($rows as $r) {
        $parts = [];
        foreach ($r as $k => $v) {
            $parts[] = "'$k' => " . php_value($v);
        }
        $out[] = $indent . '[' . implode(', ', $parts) . '],';
    }
    return implode("\n", $out);
}

$categories_raw = extract_inserts($sql, 'categories');
$pivot_raw      = extract_inserts($sql, 'blog_post_categories');
$blogs_raw      = extract_inserts($sql, 'blogs');

$firstCatForBlog = [];
foreach ($pivot_raw as $row) {
    [$id, $blog_id, $cat_id] = [$row[0], $row[1], $row[2]];
    if (!isset($firstCatForBlog[$blog_id])) $firstCatForBlog[$blog_id] = $cat_id;
}

$blog_categories = [];
foreach ($categories_raw as $row) {
    [$id, $title, $parent_id, $image, $cat_type, $creator, $slug, $status, $created_at, $updated_at] = $row;
    $types = $cat_type ? json_decode($cat_type, true) : [];
    if (!is_array($types) || !in_array('blog', $types, true)) continue;
    $blog_categories[] = [
        'id'          => $id,
        'title'       => $title,
        'description' => null,
        'icon'        => $image,
        'creator'     => $creator,
        'slug'        => $slug,
        'status'      => $status,
        'created_at'  => $created_at,
        'updated_at'  => $updated_at,
    ];
}

$blog_type_allow = ['news' => 1, 'tutorial' => 1, 'opinion' => 1];
$blogs = [];
foreach ($blogs_raw as $row) {
    [
        $id, $title, $description, $tags, $publish_date, $writer, $_mt, $_md, $_mk,
        $thumbnail_image, $image, $blog_type, $url, $slug, $show_top, $creator,
        $_privacy, $status, $created_at, $updated_at,
    ] = $row;

    $btype = isset($blog_type_allow[$blog_type]) ? $blog_type : null;
    if (is_int($show_top))      $show_top_v = $show_top === 1 ? 'yes' : 'no';
    elseif ($show_top === null) $show_top_v = 'no';
    else                        $show_top_v = ((string)$show_top === '1') ? 'yes' : 'no';

    $writer_v = null;
    if ($writer !== null && is_numeric($writer)) $writer_v = (int)$writer;

    $blogs[] = [
        'id'               => $id,
        'blog_category_id' => $firstCatForBlog[$id] ?? null,
        'title'            => $title,
        'description'      => $description,
        'content'          => $description,
        'reading_time'     => null,
        'tags'             => $tags,
        'publish_date'     => $publish_date,
        'writer'           => $writer_v,
        'thumbnail_image'  => $thumbnail_image,
        'images'           => $image,
        'blog_type'        => $btype,
        'url'              => $url,
        'show_top'         => $show_top_v,
        'contributors'     => null,
        'video_link'       => null,
        'is_featured'      => 0,
        'is_published'     => $status === 'active' ? 1 : 0,
        'creator'          => $creator,
        'slug'             => $slug,
        'status'           => in_array($status, ['active', 'inactive'], true) ? $status : 'active',
        'created_at'       => $created_at,
        'updated_at'       => $updated_at,
    ];
}

$catDir  = __DIR__ . '/Modules/Management/BlogManagement/BlogCategory/Database/Seeders/data';
$blogDir = __DIR__ . '/Modules/Management/BlogManagement/Blog/Database/Seeders/data';
@mkdir($catDir,  0777, true);
@mkdir($blogDir, 0777, true);

file_put_contents($catDir  . '/blog_categories.json', json_encode($blog_categories, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
file_put_contents($blogDir . '/blogs.json',            json_encode($blogs,            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

echo "categories: " . count($blog_categories) . PHP_EOL;
echo "blogs:      " . count($blogs) . PHP_EOL;
echo "pivot rows: " . count($pivot_raw) . PHP_EOL;
