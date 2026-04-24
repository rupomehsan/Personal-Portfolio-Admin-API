export const actions = {
    // Fetch autocomplete suggestions from backend
    async fetchSuggestions(this: any, query: string, direction = 'e2b') {
        
        if (!query || query.trim().length === 0) {
            this.suggestions = [];
            return;
        }

        this.loading = true;
        this.error = null;

        try {
            const q = encodeURIComponent(query.trim());
            const response = await (window as any).axios.get(`vocabularies?get_all=1&q=${q}&direction=${direction}`);

            if (response && response.data) {
                this.suggestions = (response.data.data || response.data).map((item: any, idx: number) => ({
                    id: item.id ?? idx,
                    word: direction === 'e2b' ? (item.english ?? item.word) : (item.bangla ?? item.word),
                    meaning: direction === 'e2b' ? (item.bangla ?? item.meaning) : (item.english ?? item.meaning),
                    raw: item,
                }));
            } else {
                this.suggestions = [];
            }
        } catch (err: any) {
            this.error = err?.message || 'Failed to fetch suggestions';
            this.suggestions = [];
        } finally {
            this.loading = false;
        }
    },

    // Get Word of the Day
    async fetchWordOfDay(this: any) {
        this.loading = true;
        this.error = null;
        try {
            const response = await (window as any).axios.get('word-of-day');
            if (response && response.data) {
                this.wordOfDay = response.data.data || response.data;
            }
        } catch (err: any) {
            this.error = err?.message || 'Failed to fetch word of the day';
        } finally {
            this.loading = false;
        }
    },

    // Get popular words
    async fetchPopularWords(this: any) {
        this.loading = true;
        this.error = null;
        try {
            const response = await (window as any).axios.get('popular-words');
            if (response && response.data) {
                this.popularWords = response.data.data || response.data;
            }
        } catch (err: any) {
            this.error = err?.message || 'Failed to fetch popular words';
        } finally {
            this.loading = false;
        }
    }
};

export default actions;
