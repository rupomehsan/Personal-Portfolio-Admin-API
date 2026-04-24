<template>
  <div class="home-page">
    <!-- Hero Section with Search -->
    <section class="hero-section bg-primary bg-gradient text-white">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-lg-10">
            <h1 class="display-3 fw-bold mb-3 animate-fade-in">
              English ⇄ Bangla Dictionary
            </h1>
            <p class="lead mb-2 fs-4">
              Your Complete Bilingual Language Companion
            </p>

            <p class="mb-5 fs-5 text-white-50">
              Discover meanings, translations, pronunciations, examples, and
              stories - all in one place
            </p>

            <!-- Main Search Section -->
            <div class="search-container position-relative">
              <div class="input-group input-group-lg shadow-lg">
                <!-- Direction toggle on the left -->
                <button
                  class="btn btn-light direction-toggle"
                  type="button"
                  @click="toggleDirection"
                  :title="directionTitle"
                >
                  <span v-if="searchDirection === 'e2b'">EN → BN</span>
                  <span v-else>BN → EN</span>
                </button>

                <input
                  type="text"
                  class="form-control border-0"
                  :placeholder="placeholderText"
                  v-model="searchQuery"
                  @input="handleSearch"
                  @focus="showSuggestions = true"
                  @blur="hideSuggestions"
                  aria-label="Search word"
                />

                <button
                  class="btn btn-success btn-lg"
                  type="button"
                  @click="performSearch"
                >
                  <i class="fas fa-search"></i>
                </button>
              </div>

              <!-- Autocomplete Dropdown -->
              <div
                v-if="showSuggestions && suggestions.length > 0"
                class="suggestions-dropdown position-absolute w-100 bg-white rounded shadow-lg mt-1"
              >
                <div
                  v-for="suggestion in suggestions"
                  :key="suggestion.id"
                  class="suggestion-item p-3 border-bottom text-start text-dark"
                  @mousedown="selectSuggestion(suggestion)"
                >
                  <div class="fw-bold">{{ suggestion.word }}</div>
                  <small class="text-muted">{{ suggestion.meaning }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Search Results Section - Moved here for immediate visibility -->
    <section
      v-if="searchResults.length > 0"
      class="search-results-section py-5 bg-light"
    >
      <div class="container">
        <div class="row justify-content-center mb-4">
          <div class="col-lg-10">
            <div class="results-header text-center mb-4">
              <h2 class="fw-bold mb-2">
                <i class="fas fa-search text-primary me-2"></i>
                Search Results
              </h2>
              <p class="text-muted">
                Found <strong>{{ searchResults.length }}</strong>
                {{ searchResults.length === 1 ? "word" : "words" }} matching
                your search
              </p>
            </div>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-10">
            <!-- Word Cards -->
            <div class="results-grid">
              <div
                v-for="word in searchResults"
                :key="word.id"
                class="word-result-card"
              >
                <div class="word-card card shadow-sm h-100">
                  <div
                    class="card-header d-flex justify-content-between align-items-start"
                  >
                    <div class="word-header flex-grow-1">
                      <h4 class="word-title mb-2 text-capitalize">
                        {{ word.word }}
                      </h4>
                      <div class="word-meta mb-2">
                        <span
                          v-if="word.part_of_speech"
                          class="badge bg-primary me-2"
                        >
                          {{ word.part_of_speech }}
                        </span>
                        <span v-if="word.label" class="badge bg-secondary me-2">
                          {{ word.label }}
                        </span>
                        <span
                          v-if="word.pronunciation"
                          class="pronunciation-badge"
                        >
                          <i class="fas fa-volume-up me-1"></i>
                          {{ word.pronunciation }}
                        </span>
                      </div>
                      <p class="meaning mb-0">{{ word.meaning }}</p>
                    </div>
                    <div class="word-actions">
                      <!-- Favorite Button -->
                      <button
                        @click="toggleFavorite(word)"
                        class="btn btn-sm btn-outline-danger me-2"
                        :class="{
                          'btn-danger text-white': isFavorite(word.id),
                        }"
                        :title="
                          isFavorite(word.id)
                            ? 'Remove from favorites'
                            : 'Add to favorites'
                        "
                      >
                        <i class="fas fa-heart"></i>
                      </button>
                      <!-- Expand Toggle -->
                      <button
                        @click="toggleWordDetails(word.id)"
                        class="btn btn-sm btn-outline-info"
                        :title="
                          isExpanded(word.id) ? 'Hide details' : 'Show details'
                        "
                      >
                        <i
                          v-if="word._loadingDetails"
                          class="fas fa-spinner fa-spin"
                        ></i>
                        <i
                          v-else
                          class="fas"
                          :class="
                            isExpanded(word.id)
                              ? 'fa-chevron-up'
                              : 'fa-chevron-down'
                          "
                        ></i>
                      </button>
                    </div>
                  </div>

                  <!-- Collapsible Details -->
                  <div v-if="isExpanded(word.id)" class="card-body pt-3">
                    <div class="row">
                      <!-- Synonyms -->
                      <div v-if="word.synonyms" class="col-md-6 mb-3">
                        <h6 class="details-heading text-success">
                          <i class="fas fa-thumbs-up me-2"></i>Synonyms
                        </h6>
                        <div class="tags-container">
                          <span
                            v-for="synonym in getSynonyms(word.synonyms)"
                            :key="synonym"
                            class="badge bg-success-subtle text-success me-1 mb-1 synonym-tag clickable"
                            @click="searchWord(synonym)"
                          >
                            {{ synonym }}
                          </span>
                        </div>
                      </div>

                      <!-- Antonyms -->
                      <div v-if="word.antonyms" class="col-md-6 mb-3">
                        <h6 class="details-heading text-danger">
                          <i class="fas fa-thumbs-down me-2"></i>Antonyms
                        </h6>
                        <div class="tags-container">
                          <span
                            v-for="antonym in getAntonyms(word.antonyms)"
                            :key="antonym"
                            class="badge bg-danger-subtle text-danger me-1 mb-1 antonym-tag clickable"
                            @click="searchWord(antonym)"
                          >
                            {{ antonym }}
                          </span>
                        </div>
                      </div>
                    </div>

                    <!-- Example Sentences -->
                    <div
                      v-if="
                        word.example_sentence &&
                        word.example_sentence.length > 0
                      "
                      class="mb-3"
                    >
                      <h6 class="details-heading text-info">
                        <i class="fas fa-quote-left me-2"></i>Example Sentences
                      </h6>
                      <div
                        v-for="(example, index) in getExampleSentences(
                          word.example_sentence
                        )"
                        :key="index"
                        class="example-sentence mb-2 p-3 rounded"
                      >
                        <blockquote class="mb-1 fw-medium">
                          <em>"{{ example.sentence }}"</em>
                        </blockquote>
                        <small class="text-muted">{{ example.meaning }}</small>
                      </div>
                    </div>

                    <!-- Story Section -->
                    <div
                      v-if="(word.stories && word.stories.length) || word.story"
                      class="mb-3"
                    >
                      <h6 class="details-heading text-primary">
                        <i class="fas fa-book-open me-2"></i>Story
                      </h6>

                      <!-- Multiple stories from API -->
                      <div v-if="word.stories && word.stories.length">
                        <div
                          v-for="story in word.stories"
                          :key="story.id"
                          class="story-block mb-3 p-3 bg-white rounded border"
                        >
                          <div
                            class="story-content mb-2"
                            v-html="
                              getHighlightedStoryHtml(
                                story.story,
                                word.word,
                                word._selectedKeyword
                              )
                            "
                            @click="handleStoryContentClick($event, word)"
                          ></div>
                          <div class="story-keywords">
                            <small class="text-muted"
                              >Keywords: {{ story.keyword }}</small
                            >
                          </div>
                        </div>
                      </div>

                      <!-- Single story fallback -->
                      <div
                        v-else-if="word.story"
                        class="story-block mb-3 p-3 bg-white rounded border"
                      >
                        <div
                          class="story-content mb-2"
                          v-html="
                            getHighlightedStoryHtml(
                              word.story,
                              word.word,
                              word._selectedKeyword
                            )
                          "
                          @click="handleStoryContentClick($event, word)"
                        ></div>
                      </div>
                    </div>

                    <!-- Keywords -->
                    <div v-if="word.keyword" class="mb-3">
                      <h6 class="details-heading text-secondary">
                        <i class="fas fa-tags me-2"></i>Keywords
                      </h6>
                      <div class="tags-container">
                        <span
                          v-for="kw in (word.keyword || '')
                            .split(',')
                            .map((k) => k.trim())
                            .filter((k) => k)"
                          :key="kw"
                          class="badge bg-secondary-subtle text-secondary me-1 mb-1 keyword-badge clickable"
                          @click="onKeywordClick(kw, word)"
                        >
                          {{ kw }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Statistics Section -->
    <section class="statistics-section py-5 bg-white">
      <div class="container">
        <div class="row text-center">
          <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
            <div class="stat-card">
              <div class="stat-icon text-primary mb-3">
                <i class="fas fa-book fa-3x"></i>
              </div>
              <h3 class="stat-number fw-bold mb-2">50,000+</h3>
              <p class="stat-label text-muted mb-0">Words</p>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
            <div class="stat-card">
              <div class="stat-icon text-success mb-3">
                <i class="fas fa-comment-dots fa-3x"></i>
              </div>
              <h3 class="stat-number fw-bold mb-2">100,000+</h3>
              <p class="stat-label text-muted mb-0">Examples</p>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
            <div class="stat-card">
              <div class="stat-icon text-warning mb-3">
                <i class="fas fa-book-reader fa-3x"></i>
              </div>
              <h3 class="stat-number fw-bold mb-2">5,000+</h3>
              <p class="stat-label text-muted mb-0">Stories</p>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="stat-card">
              <div class="stat-icon text-danger mb-3">
                <i class="fas fa-users fa-3x"></i>
              </div>
              <h3 class="stat-number fw-bold mb-2">10,000+</h3>
              <p class="stat-label text-muted mb-0">Active Users</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Word of the Day Section -->
    <section v-if="wordOfDay" class="word-of-day-section py-5 bg-light">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="section-header text-center mb-5">
              <h2 class="section-title fw-bold mb-2">
                <i class="fas fa-calendar-day text-primary me-2"></i>
                Word of the Day
              </h2>
              <p class="text-muted">
                Expand your vocabulary with a new word every day
              </p>
            </div>
            <div class="card shadow-lg border-0 word-day-card">
              <div class="card-body p-5">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <h3 class="display-6 fw-bold text-primary mb-3">
                      {{ wordOfDay.word }}
                    </h3>
                    <div class="mb-3">
                      <span
                        v-if="wordOfDay.pronunciation"
                        class="badge bg-info text-white me-2 px-3 py-2"
                      >
                        <i class="fas fa-volume-up me-1"></i>
                        {{ wordOfDay.pronunciation }}
                      </span>
                      <span
                        v-if="wordOfDay.part_of_speech"
                        class="badge bg-primary px-3 py-2"
                      >
                        {{ wordOfDay.part_of_speech }}
                      </span>
                    </div>
                    <h5 class="text-dark mb-3">{{ wordOfDay.meaning }}</h5>
                    <div
                      v-if="wordOfDay.example_sentence"
                      class="example-block p-3 bg-light rounded"
                    >
                      <small class="text-muted d-block mb-1">Example:</small>
                      <em class="text-dark"
                        >"{{ getFirstExample(wordOfDay.example_sentence) }}"</em
                      >
                    </div>
                  </div>
                  <div class="col-md-4 text-center">
                    <div class="calendar-icon mb-3">
                      <i
                        class="far fa-calendar-alt fa-5x text-primary opacity-25"
                      ></i>
                    </div>
                    <button
                      @click="getNewWordOfDay"
                      class="btn btn-primary btn-lg"
                    >
                      <i class="fas fa-sync-alt me-2"></i>
                      Get New Word
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features-section py-5 bg-white">
      <div class="container">
        <div class="section-header text-center mb-5">
          <h2 class="section-title fw-bold mb-2">Why Choose Our Dictionary?</h2>
          <p class="text-muted">
            Comprehensive features to enhance your language learning journey
          </p>
        </div>
        <div class="row g-4">
          <div class="col-lg-4 col-md-6">
            <div class="feature-box text-center p-4 h-100">
              <div class="feature-icon-wrapper mb-4">
                <div
                  class="feature-icon bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                >
                  <i class="fas fa-language fa-2x"></i>
                </div>
              </div>
              <h4 class="fw-bold mb-3">Bidirectional Translation</h4>
              <p class="text-muted">
                Search in English or Bangla and get instant, accurate
                translations in both directions
              </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="feature-box text-center p-4 h-100">
              <div class="feature-icon-wrapper mb-4">
                <div
                  class="feature-icon bg-success bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                >
                  <i class="fas fa-volume-up fa-2x"></i>
                </div>
              </div>
              <h4 class="fw-bold mb-3">Pronunciation Guide</h4>
              <p class="text-muted">
                Learn proper pronunciation with phonetic transcriptions for
                every English word
              </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="feature-box text-center p-4 h-100">
              <div class="feature-icon-wrapper mb-4">
                <div
                  class="feature-icon bg-info bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                >
                  <i class="fas fa-comment-dots fa-2x"></i>
                </div>
              </div>
              <h4 class="fw-bold mb-3">Real Examples</h4>
              <p class="text-muted">
                Understand word usage with thousands of example sentences in
                context
              </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="feature-box text-center p-4 h-100">
              <div class="feature-icon-wrapper mb-4">
                <div
                  class="feature-icon bg-warning bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                >
                  <i class="fas fa-book-open fa-2x"></i>
                </div>
              </div>
              <h4 class="fw-bold mb-3">Learning Stories</h4>
              <p class="text-muted">
                Engage with vocabulary through interesting stories and
                contextual learning
              </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="feature-box text-center p-4 h-100">
              <div class="feature-icon-wrapper mb-4">
                <div
                  class="feature-icon bg-danger bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                >
                  <i class="fas fa-heart fa-2x"></i>
                </div>
              </div>
              <h4 class="fw-bold mb-3">Save Favorites</h4>
              <p class="text-muted">
                Bookmark words you want to remember and build your personal
                vocabulary list
              </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="feature-box text-center p-4 h-100">
              <div class="feature-icon-wrapper mb-4">
                <div
                  class="feature-icon bg-secondary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                >
                  <i class="fas fa-mobile-alt fa-2x"></i>
                </div>
              </div>
              <h4 class="fw-bold mb-3">Mobile Friendly</h4>
              <p class="text-muted">
                Access the dictionary anywhere, anytime on your phone, tablet,
                or computer
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Popular Categories Section -->
    <section class="popular-categories-section py-5 bg-light">
      <div class="container">
        <div class="section-header text-center mb-5">
          <h2 class="section-title fw-bold mb-2">Explore Popular Categories</h2>
          <p class="text-muted">
            Quick access to commonly searched vocabulary topics
          </p>
        </div>
        <div class="row g-3">
          <div
            class="col-lg-3 col-md-4 col-sm-6"
            v-for="category in popularCategories"
            :key="category.name"
          >
            <div
              class="category-card card h-100 border-0 shadow-sm"
              @click="searchWord(category.keyword)"
            >
              <div class="card-body text-center p-4">
                <div class="category-icon mb-3">
                  <i
                    :class="category.icon + ' fa-3x'"
                    :style="{ color: category.color }"
                  ></i>
                </div>
                <h5 class="card-title mb-2">{{ category.name }}</h5>
                <small class="text-muted">{{ category.count }}+ words</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works-section py-5 bg-white">
      <div class="container">
        <div class="section-header text-center mb-5">
          <h2 class="section-title fw-bold mb-2">How It Works</h2>
          <p class="text-muted">Start learning in three simple steps</p>
        </div>
        <div class="row g-4">
          <div class="col-lg-4">
            <div class="step-card text-center p-4">
              <div class="step-number-wrapper mb-4">
                <div
                  class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                >
                  <span class="fw-bold fs-2">1</span>
                </div>
              </div>
              <div class="step-icon mb-3">
                <i class="fas fa-search fa-3x text-primary"></i>
              </div>
              <h4 class="fw-bold mb-3">Search</h4>
              <p class="text-muted">
                Type any word in English or Bangla in the search box above
              </p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="step-card text-center p-4">
              <div class="step-number-wrapper mb-4">
                <div
                  class="step-number bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                >
                  <span class="fw-bold fs-2">2</span>
                </div>
              </div>
              <div class="step-icon mb-3">
                <i class="fas fa-book-open fa-3x text-success"></i>
              </div>
              <h4 class="fw-bold mb-3">Explore</h4>
              <p class="text-muted">
                View meanings, examples, synonyms, and stories for comprehensive
                understanding
              </p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="step-card text-center p-4">
              <div class="step-number-wrapper mb-4">
                <div
                  class="step-number bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                >
                  <span class="fw-bold fs-2">3</span>
                </div>
              </div>
              <div class="step-icon mb-3">
                <i class="fas fa-graduation-cap fa-3x text-warning"></i>
              </div>
              <h4 class="fw-bold mb-3">Learn</h4>
              <p class="text-muted">
                Save favorites and track your progress as you expand your
                vocabulary
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section py-5 bg-white">
      <div class="container">
        <div class="section-header text-center mb-5">
          <h2 class="section-title fw-bold mb-2">Frequently Asked Questions</h2>
          <p class="text-muted">
            Everything you need to know about using the dictionary
          </p>
        </div>
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="accordion" id="faqAccordion">
              <div class="accordion-item border-0 mb-3 shadow-sm">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq1"
                  >
                    <i class="fas fa-question-circle text-primary me-3"></i>
                    How do I search for words?
                  </button>
                </h2>
                <div
                  id="faq1"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Simply type any word in English or Bangla in the search box
                    above. You can use the language toggle button (EN → BN or BN
                    → EN) to switch search direction. As you type, suggestions
                    will appear to help you find the right word quickly.
                  </div>
                </div>
              </div>

              <div class="accordion-item border-0 mb-3 shadow-sm">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq2"
                  >
                    <i class="fas fa-question-circle text-primary me-3"></i>
                    What information is provided for each word?
                  </button>
                </h2>
                <div
                  id="faq2"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Each word entry includes the translation, pronunciation
                    guide, part of speech, meaning, example sentences, synonyms,
                    antonyms, related keywords, and sometimes learning stories
                    to help you understand the word in context.
                  </div>
                </div>
              </div>

              <div class="accordion-item border-0 mb-3 shadow-sm">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq3"
                  >
                    <i class="fas fa-question-circle text-primary me-3"></i>
                    Can I save my favorite words?
                  </button>
                </h2>
                <div
                  id="faq3"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Yes! Click the heart icon next to any word to add it to your
                    favorites. Your favorites are saved locally in your browser,
                    so you can access them anytime you return to the site.
                  </div>
                </div>
              </div>

              <div class="accordion-item border-0 mb-3 shadow-sm">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq4"
                  >
                    <i class="fas fa-question-circle text-primary me-3"></i>
                    Is the dictionary available on mobile devices?
                  </button>
                </h2>
                <div
                  id="faq4"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Absolutely! Our dictionary is fully responsive and works
                    perfectly on smartphones, tablets, and desktop computers.
                    You can access it from any device with a web browser.
                  </div>
                </div>
              </div>

              <div class="accordion-item border-0 mb-3 shadow-sm">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq5"
                  >
                    <i class="fas fa-question-circle text-primary me-3"></i>
                    How often is the dictionary updated?
                  </button>
                </h2>
                <div
                  id="faq5"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    We continuously add new words, examples, and stories to keep
                    the dictionary current and comprehensive. The Word of the
                    Day is updated daily to help you learn something new every
                    day.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-gradient-primary text-white">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-lg-8">
            <h2 class="display-5 fw-bold mb-3">
              Ready to Expand Your Vocabulary?
            </h2>
            <p class="lead mb-4">
              Join thousands of learners who are improving their English and
              Bangla skills every day
            </p>
            <button
              class="btn btn-light btn-lg px-5 py-3"
              @click="scrollToSearch"
            >
              <i class="fas fa-search me-2"></i>
              Start Searching Now
            </button>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { store as useHomeStore } from "./Store";

export default {
  name: "HomePage",
  data() {
    return {
      // 'e2b' = English to Bangla, 'b2e' = Bangla to English
      searchDirection: "e2b",
      searchQuery: "",
      showSuggestions: false,
      // will hold the Pinia store instance
      homeStore: null,
      // Search results from database
      searchResults: [],
      // Track expanded word details
      expandedWords: new Set(),
      // Favorite words (store in localStorage)
      favorites: new Set(),
      // Popular categories
      popularCategories: [
        {
          name: "Common Words",
          keyword: "common",
          icon: "fas fa-star",
          color: "#667eea",
          count: 500,
        },
        {
          name: "Business",
          keyword: "business",
          icon: "fas fa-briefcase",
          color: "#f093fb",
          count: 300,
        },
        {
          name: "Technology",
          keyword: "technology",
          icon: "fas fa-laptop-code",
          color: "#4facfe",
          count: 400,
        },
        {
          name: "Education",
          keyword: "education",
          icon: "fas fa-graduation-cap",
          color: "#43e97b",
          count: 350,
        },
        {
          name: "Health",
          keyword: "health",
          icon: "fas fa-heartbeat",
          color: "#fa709a",
          count: 250,
        },
        {
          name: "Travel",
          keyword: "travel",
          icon: "fas fa-plane",
          color: "#30cfd0",
          count: 200,
        },
        {
          name: "Food",
          keyword: "food",
          icon: "fas fa-utensils",
          color: "#f77062",
          count: 180,
        },
        {
          name: "Sports",
          keyword: "sports",
          icon: "fas fa-football-ball",
          color: "#a8edea",
          count: 220,
        },
      ],
    };
  },
  created() {
    this.homeStore = useHomeStore();
    // Load favorites from localStorage
    this.loadFavorites();
  },
  computed: {
    placeholderText() {
      return this.searchDirection === "e2b"
        ? "Search English word..."
        : "Search Bangla word...";
    },
    directionTitle() {
      return this.searchDirection === "e2b"
        ? "Switch to Bangla → English"
        : "Switch to English → Bangla";
    },
    suggestions() {
      return this.homeStore ? this.homeStore.suggestions : [];
    },
    wordOfDay() {
      return this.homeStore ? this.homeStore.wordOfDay : null;
    },
    popularWords() {
      return this.homeStore ? this.homeStore.popularWords : [];
    },
  },
  methods: {
    handleSearch() {
      if (
        this.searchQuery &&
        this.searchQuery.trim().length > 0 &&
        this.homeStore
      ) {
        this.homeStore.fetchSuggestions(this.searchQuery, this.searchDirection);
        this.showSuggestions = true;
      } else if (this.homeStore) {
        this.homeStore.suggestions = [];
        this.showSuggestions = false;
      }
    },
    performSearch() {
      if (this.searchQuery.trim()) {
        this.hideSuggestions();
        this.fetchSearchResults();
      }
    },
    async fetchSearchResults() {
      try {
        const response = await window.axios.get("vocabularies", {
          params: {
            get_all: 1,
            q: this.searchQuery,
            direction: this.searchDirection,
            limit: 20,
          },
        });

        if (response && response.data && response.data.data) {
          this.searchResults = response.data.data;
        } else {
          this.searchResults = [];
        }
      } catch (error) {
        console.error("Search failed:", error);
        this.searchResults = [];
      }
    },
    async selectSuggestion(suggestion) {
      // If suggestion contains slug, fetch single record via vocabularies/{slug}
      this.searchQuery = suggestion.word;
      this.showSuggestions = false;

      const slug = suggestion.slug || (suggestion.raw && suggestion.raw.slug);
      if (slug) {
        await this.fetchSingleWord(slug);
        return;
      }

      // Fallback to performSearch (list search)
      this.performSearch();
    },

    async fetchSingleWord(slug) {
      try {
        // fetch single vocabulary by slug
        const response = await window.axios.get(`vocabularies/${slug}`);
        if (response && response.data) {
          const payload = response.data.data || response.data;
          // ensure array for searchResults (single item)
          this.searchResults = [payload];
          // mark loaded details
          if (this.searchResults[0]) {
            this.searchResults[0]._loadedDetails = true;
          }
        } else {
          this.searchResults = [];
        }
      } catch (err) {
        console.error("Failed to fetch single word:", err);
        this.searchResults = [];
      }
    },
    toggleDirection() {
      this.searchDirection = this.searchDirection === "e2b" ? "b2e" : "e2b";
      if (this.searchQuery.trim().length > 0) {
        this.handleSearch();
      } else if (this.homeStore) {
        this.homeStore.suggestions = [];
      }
    },
    hideSuggestions() {
      // Delay hiding to allow click on suggestions
      setTimeout(() => {
        this.showSuggestions = false;
      }, 200);
    },
    searchWord(word) {
      this.searchQuery = word;
      this.performSearch();
    },
    getNewWordOfDay() {
      if (this.homeStore) {
        this.homeStore.fetchWordOfDay();
      }
    },
    // Word details and favorites methods
    async toggleWordDetails(wordId) {
      // find the index and item
      const idx = this.searchResults.findIndex((w) => w.id === wordId);
      if (idx === -1) return;

      const word = this.searchResults[idx];

      // If details are already loading, do nothing
      if (word._loadingDetails) return;

      // If details not loaded, fetch from API using slug
      if (!word._loadedDetails && word.slug) {
        this.$set
          ? this.$set(word, "_loadingDetails", true)
          : (word._loadingDetails = true);
        try {
          const response = await window.axios.get(`vocabularies/${word.slug}`);
          if (response && response.data) {
            const payload = response.data.data || response.data;
            // Merge incoming fields into the word object
            Object.keys(payload).forEach((key) => {
              // Avoid overriding internal flags
              if (["_loadingDetails", "_loadedDetails"].includes(key)) return;
              this.$set
                ? this.$set(word, key, payload[key])
                : (word[key] = payload[key]);
            });
            this.$set
              ? this.$set(word, "_loadedDetails", true)
              : (word._loadedDetails = true);
          }
        } catch (err) {
          console.error("Failed to load word details:", err);
        } finally {
          this.$set
            ? this.$set(word, "_loadingDetails", false)
            : (word._loadingDetails = false);
        }
      }

      // toggle expansion
      if (this.expandedWords.has(wordId)) {
        this.expandedWords.delete(wordId);
      } else {
        this.expandedWords.add(wordId);
      }
    },
    isExpanded(wordId) {
      return this.expandedWords.has(wordId);
    },
    toggleFavorite(word) {
      if (this.favorites.has(word.id)) {
        this.favorites.delete(word.id);
      } else {
        this.favorites.add(word.id);
      }
      this.saveFavorites();
    },
    isFavorite(wordId) {
      return this.favorites.has(wordId);
    },
    loadFavorites() {
      try {
        const saved = localStorage.getItem("dictionary_favorites");
        if (saved) {
          this.favorites = new Set(JSON.parse(saved));
        }
      } catch (error) {
        console.error("Failed to load favorites:", error);
      }
    },
    saveFavorites() {
      try {
        localStorage.setItem(
          "dictionary_favorites",
          JSON.stringify([...this.favorites])
        );
      } catch (error) {
        console.error("Failed to save favorites:", error);
      }
    },
    getSynonyms(synonymsString) {
      if (!synonymsString) return [];
      return synonymsString
        .split(",")
        .map((s) => s.trim())
        .filter((s) => s);
    },
    getAntonyms(antonymsString) {
      if (!antonymsString) return [];
      return antonymsString
        .split(",")
        .map((s) => s.trim())
        .filter((s) => s);
    },
    getExampleSentences(examples) {
      if (!examples) return [];
      if (typeof examples === "string") {
        try {
          return JSON.parse(examples);
        } catch {
          return [];
        }
      }
      return Array.isArray(examples) ? examples : [];
    },
    /**
     * Return story HTML with the matched word highlighted (red + bold).
     * We perform a simple, safe replace on text nodes by creating a DOM
     * fragment and walking text nodes to avoid corrupting existing tags.
     */
    getHighlightedStoryHtml(storyHtml, matchWord, selectedKeyword) {
      if (!storyHtml) return "";
      // if no matchWord provided, still can highlight selectedKeyword
      if (!matchWord && !selectedKeyword) return storyHtml;

      try {
        const parser = new DOMParser();
        const doc = parser.parseFromString(storyHtml, "text/html");
        const target = selectedKeyword || matchWord;
        const walker = doc.createTreeWalker(
          doc.body,
          NodeFilter.SHOW_TEXT,
          null,
          false
        );
        const nodesToReplace = [];

        while (walker.nextNode()) {
          const node = walker.currentNode;
          if (node.nodeValue && /[A-Za-z]/.test(node.nodeValue)) {
            nodesToReplace.push(node);
          }
        }

        // Wrap every ASCII English word in a clickable span. If it equals target/selectedKeyword,
        // add matched classes.
        nodesToReplace.forEach((textNode) => {
          const text = textNode.nodeValue;
          const replaced = text.replace(/([A-Za-z]+)/g, (m) => {
            const isMatch = target && m.toLowerCase() === target.toLowerCase();
            const isSelected =
              selectedKeyword &&
              m.toLowerCase() === selectedKeyword.toLowerCase();
            const classes = ["story-word"];
            if (isMatch) classes.push("matched-word");
            if (isSelected) classes.push("matched-clicked");
            // Escape the word for attribute
            const safe = m.replace(/"/g, "&quot;");
            return `<span class="${classes.join(
              " "
            )}" data-word="${safe}">${m}</span>`;
          });
          const span = doc.createElement("span");
          span.innerHTML = replaced;
          textNode.parentNode.replaceChild(span, textNode);
        });

        return doc.body.innerHTML;
      } catch (e) {
        // On any failure, fallback to basic replace (may break HTML) but avoid crashing
        try {
          const target = selectedKeyword || matchWord;
          const re = new RegExp(`\\b(${this.escapeRegExp(target)})\\b`, "gi");
          const replacementClass = selectedKeyword
            ? "matched-word matched-clicked"
            : "matched-word";
          return storyHtml.replace(
            re,
            `<span class="${replacementClass}">$1</span>`
          );
        } catch (_) {
          return storyHtml;
        }
      }
    },
    /**
     * Handle click inside story HTML; if a matched-word span was clicked, treat it as a keyword click.
     */
    handleStoryContentClick(event, word) {
      const el = event.target;
      // Prefer story-word (all English words wrapped). fall back to matched-word if needed.
      const storyWordEl = el.closest && el.closest(".story-word");
      const matched =
        storyWordEl || (el.closest && el.closest(".matched-word"));
      if (matched) {
        // read data-word if present, otherwise fallback to textContent
        const kw =
          (matched.getAttribute && matched.getAttribute("data-word")) ||
          (matched.textContent && matched.textContent.trim());
        if (kw) {
          this.onKeywordClick(kw, word);
        }
      }
    },
    onKeywordClick(keyword, word) {
      // mark selected keyword on the word so getHighlightedStoryHtml can style it
      this.$set
        ? this.$set(word, "_selectedKeyword", keyword)
        : (word._selectedKeyword = keyword);
      // re-render by forcing Vue reactivity (word object updated)
      // also set the searchQuery and perform search for clicked keyword
      this.searchQuery = keyword;
      this.performSearch();
    },
    escapeRegExp(string) {
      return string.replace(/[.*+?^${}()|[\\]\\\\]/g, "\\$&");
    },
    getFirstExample(exampleSentence) {
      if (!exampleSentence) return "";
      const examples = this.getExampleSentences(exampleSentence);
      if (examples.length > 0) {
        const firstExample = examples[0];
        return firstExample.sentence || firstExample.meaning || firstExample;
      }
      return "";
    },
    scrollToSearch() {
      const heroSection = document.querySelector(".hero-section");
      if (heroSection) {
        heroSection.scrollIntoView({ behavior: "smooth", block: "start" });
        // Focus on search input after scrolling
        setTimeout(() => {
          const searchInput = document.querySelector(
            '.hero-section input[type="text"]'
          );
          if (searchInput) searchInput.focus();
        }, 600);
      }
    },
  },
  mounted() {
    // Fetch word of the day on component mount
    if (this.homeStore) {
      this.homeStore.fetchWordOfDay();
    }
  },
};
</script>

<style scoped>
.home-page {
  min-height: 100vh;
}

/* Hero Section */
.hero-section {
  padding: 5rem 0 6rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  position: relative;
  overflow: visible;
}

.hero-section::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: 0.3;
}

.hero-section .container {
  position: relative;
  z-index: 1;
}

.animate-fade-in {
  animation: fadeIn 1s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Statistics Section */
.statistics-section {
  padding: 4rem 0;
  border-bottom: 1px solid #e9ecef;
}

.stat-card {
  transition: transform 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-10px);
}

.stat-number {
  font-size: 2.5rem;
  color: #2c3e50;
}

.stat-label {
  font-size: 1rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Section Headers */
.section-header {
  margin-bottom: 3rem;
}

.section-title {
  font-size: 2.5rem;
  color: #2c3e50;
}

/* Word of the Day */
.word-of-day-section {
  padding: 5rem 0;
}

.word-day-card {
  border-radius: 20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.word-day-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.calendar-icon {
  position: relative;
}

/* Features Section */
.features-section {
  padding: 5rem 0;
}

.feature-box {
  background: #ffffff;
  border-radius: 15px;
  transition: all 0.3s ease;
  border: 1px solid #e9ecef;
}

.feature-box:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  border-color: transparent;
}

.feature-icon {
  width: 80px;
  height: 80px;
  font-size: 2rem;
  transition: transform 0.3s ease;
}

.feature-box:hover .feature-icon {
  transform: scale(1.1) rotate(5deg);
}

/* Popular Categories */
.popular-categories-section {
  padding: 5rem 0;
}

.category-card {
  cursor: pointer;
  transition: all 0.3s ease;
  border-radius: 15px;
  background: white;
}

.category-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15) !important;
}

.category-icon {
  transition: transform 0.3s ease;
}

.category-card:hover .category-icon {
  transform: scale(1.2);
}

/* How It Works */
.how-it-works-section {
  padding: 5rem 0;
}

.step-card {
  background: #f8f9fa;
  border-radius: 15px;
  transition: all 0.3s ease;
}

.step-card:hover {
  background: white;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.step-number {
  width: 70px;
  height: 70px;
  font-size: 2rem;
}

.step-icon {
  transition: transform 0.3s ease;
}

.step-card:hover .step-icon {
  transform: scale(1.15);
}

/* FAQ Section */
.faq-section {
  padding: 5rem 0;
}

.accordion-item {
  border-radius: 10px !important;
  overflow: hidden;
  margin-bottom: 1rem;
}

.accordion-button {
  background: white;
  font-weight: 600;
  font-size: 1.1rem;
  padding: 1.25rem 1.5rem;
  border-radius: 10px !important;
}

.accordion-button:not(.collapsed) {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.accordion-button:not(.collapsed) .fa-question-circle {
  color: white !important;
}

.accordion-button:focus {
  box-shadow: none;
  border-color: transparent;
}

.accordion-body {
  padding: 1.5rem;
  background: #f8f9fa;
  font-size: 1rem;
  line-height: 1.6;
  color: #495057;
}

/* CTA Section */
.cta-section {
  padding: 5rem 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  position: relative;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.search-container {
  max-width: 600px;
  margin: 0 auto;
}

.direction-toggle {
  border-top-left-radius: 0.375rem;
  border-bottom-left-radius: 0.375rem;
  border-right: 1px solid rgba(0, 0, 0, 0.05);
  min-width: 78px;
}

.suggestions-dropdown {
  z-index: 9999;
  max-height: 300px;
  overflow-y: auto;
  border: 1px solid #dee2e6;
}

.suggestion-item {
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.suggestion-item:hover {
  background-color: #f8f9fa;
}

.suggestion-item:last-child {
  border-bottom: none;
}

.word-of-day .card {
  border-radius: 15px;
}

.popular-tags .badge {
  transition: all 0.3s ease;
  cursor: pointer;
  border: 1px solid transparent;
}

.popular-tags .badge:hover {
  background-color: var(--bs-primary) !important;
  color: white !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.feature-card {
  padding: 2rem 1rem;
}

.feature-icon {
  transition: transform 0.3s ease;
}

.feature-card:hover .feature-icon {
  transform: scale(1.1);
}

.cursor-pointer {
  cursor: pointer;
}

/* Mobile Optimizations */
@media (max-width: 768px) {
  .hero-section {
    padding: 3rem 0 4rem;
  }

  .hero-section h1 {
    font-size: 2rem;
  }

  .display-3 {
    font-size: 2rem !important;
  }

  .hero-section p {
    font-size: 1rem;
  }

  .lead {
    font-size: 1.1rem !important;
  }

  .input-group-lg .form-control,
  .input-group-lg .btn {
    padding: 0.75rem 1rem;
    font-size: 1rem;
  }

  .section-title {
    font-size: 1.8rem;
  }

  .statistics-section,
  .word-of-day-section,
  .features-section,
  .popular-categories-section,
  .how-it-works-section,
  .cta-section {
    padding: 3rem 0;
  }

  .stat-number {
    font-size: 2rem;
  }

  .word-day-card .card-body {
    padding: 2rem !important;
  }

  .feature-icon {
    width: 60px;
    height: 60px;
  }

  .step-number {
    width: 60px;
    height: 60px;
  }

  .popular-tags {
    gap: 0.5rem !important;
  }

  .popular-tags .badge {
    font-size: 0.85rem !important;
    padding: 0.5rem 0.75rem !important;
  }
}

@media (max-width: 576px) {
  .hero-section h1 {
    font-size: 1.75rem;
  }

  .display-3 {
    font-size: 1.75rem !important;
  }

  .display-4 {
    font-size: 1.5rem !important;
  }

  .display-5 {
    font-size: 1.75rem !important;
  }

  .display-6 {
    font-size: 1.5rem !important;
  }

  .feature-card {
    padding: 1.5rem 0.5rem;
  }

  .section-title {
    font-size: 1.5rem;
  }

  .stat-number {
    font-size: 1.75rem;
  }

  .word-day-card .card-body {
    padding: 1.5rem !important;
  }

  .cta-section .btn-lg {
    padding: 0.75rem 2rem !important;
    font-size: 1rem;
  }
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Loading animation for search */
.search-container .btn:disabled {
  cursor: not-allowed;
}

/* Focus styles for accessibility */
.form-control:focus {
  border-color: #86b7fe;
  outline: 0;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Animation for word of the day card */
.word-of-day .card {
  transition: transform 0.3s ease;
}

.word-of-day .card:hover {
  transform: translateY(-5px);
}

/* Search Results Styles */
.search-results-section {
  background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
  min-height: 200px;
}

.results-header {
  animation: fadeInDown 0.6s ease-out;
}

.results-header h2 {
  color: #2c3e50;
  font-size: 2rem;
}

.results-header strong {
  color: #667eea;
  font-weight: 700;
}

.results-grid {
  display: grid;
  gap: 1.5rem;
  animation: fadeIn 0.8s ease-out;
}

.word-result-card {
  animation: slideInUp 0.5s ease-out;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.word-card {
  border: 1px solid #e0e0e0;
  border-radius: 16px;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  background: white;
  position: relative;
}

.word-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
  transform: scaleX(0);
  transition: transform 0.4s ease;
}

.word-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 35px rgba(102, 126, 234, 0.15) !important;
  border-color: #667eea;
}

.word-card:hover::before {
  transform: scaleX(1);
}

.word-card .card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-bottom: 1px solid #dee2e6;
  padding: 1.5rem;
}

.word-header {
  flex: 1;
}

.word-title {
  color: #2c3e50;
  font-weight: 700;
  font-size: 1.75rem;
  letter-spacing: -0.025em;
}

.word-meta {
  margin-bottom: 0.75rem;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.pronunciation-badge {
  color: #64748b;
  font-size: 0.875rem;
  font-weight: 500;
}

.meaning {
  color: #495057;
  font-size: 1.125rem;
  line-height: 1.6;
  font-weight: 500;
}

.word-actions {
  display: flex;
  gap: 0.5rem;
  flex-shrink: 0;
}

.details-heading {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.clickable {
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.synonym-tag,
.antonym-tag,
.keyword-badge {
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.875rem;
  padding: 0.5rem 0.75rem;
  border-radius: 8px;
  font-weight: 500;
}

.synonym-tag:hover {
  background-color: var(--bs-success) !important;
  color: white !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.antonym-tag:hover {
  background-color: var(--bs-danger) !important;
  color: white !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.keyword-badge:hover {
  background-color: var(--bs-secondary) !important;
  color: white !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.example-sentence {
  border-left: 4px solid #17a2b8;
  background: linear-gradient(
    90deg,
    rgba(23, 162, 184, 0.08) 0%,
    rgba(255, 255, 255, 1) 100%
  );
  border-radius: 8px;
  transition: all 0.3s ease;
}

.example-sentence:hover {
  border-left-width: 6px;
  background: linear-gradient(
    90deg,
    rgba(23, 162, 184, 0.12) 0%,
    rgba(255, 255, 255, 1) 100%
  );
  box-shadow: 0 2px 8px rgba(23, 162, 184, 0.1);
}

.example-sentence blockquote {
  font-style: italic;
  color: #2c3e50;
  margin-bottom: 0.5rem;
  font-size: 1rem;
  line-height: 1.6;
}

.story-block {
  background: #f8f9fa !important;
  border: 1px solid #dee2e6 !important;
  border-radius: 12px !important;
  transition: all 0.3s ease;
}

.story-block:hover {
  border-color: #667eea !important;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.story-content {
  line-height: 1.8;
  color: #2c3e50;
}

/* Highlight matched words inside story HTML */
.matched-word {
  color: #c82333; /* bootstrap danger-like red */
  font-weight: 700;
}
.matched-clicked {
  text-decoration: underline;
}
.keyword-badge {
  cursor: pointer;
}
/* Ensure styles apply to HTML injected via v-html when using scoped CSS */
::v-deep .matched-word {
  color: #c82333;
  font-weight: 700;
}
::v-deep .matched-clicked {
  text-decoration: underline;
}
/* Make story words clickable with pointer cursor */
.story-word {
  cursor: pointer;
}
::v-deep .story-word {
  cursor: pointer;
}

.card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-bottom: 1px solid #dee2e6;
  padding: 1.25rem;
}

.card-body {
  padding: 1.25rem;
  background: white;
}

/* Responsive adjustments for search results */
@media (max-width: 768px) {
  .word-card .card-header {
    flex-direction: column;
    align-items: flex-start !important;
  }

  .word-actions {
    margin-top: 1rem;
    align-self: flex-end;
  }

  .word-title {
    font-size: 1.25rem;
  }

  .meaning {
    font-size: 1rem;
  }
}
</style>
