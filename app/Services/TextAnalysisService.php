<?php

namespace App\Services;

class TextAnalysisService
{
    /**
     * Portuguese stop words to filter out during analysis.
     */
    private const STOP_WORDS = [
        'o', 'a', 'os', 'as', 'um', 'uma', 'uns', 'umas',
        'de', 'do', 'da', 'dos', 'das', 'dum', 'duma', 'duns', 'dumas',
        'em', 'no', 'na', 'nos', 'nas', 'num', 'numa', 'nuns', 'numas',
        'por', 'pelo', 'pela', 'pelos', 'pelas',
        'ao', 'aos', 'à', 'às',
        'para', 'pro', 'pra', 'pros', 'pras',
        'com', 'sem', 'sob', 'sobre',
        'entre', 'até', 'desde', 'após', 'ante', 'contra', 'perante',
        'e', 'ou', 'mas', 'porém', 'contudo', 'todavia', 'entretanto',
        'que', 'qual', 'quais', 'quanto', 'quantos', 'quanta', 'quantas',
        'este', 'esta', 'estes', 'estas', 'isto',
        'esse', 'essa', 'esses', 'essas', 'isso',
        'aquele', 'aquela', 'aqueles', 'aquelas', 'aquilo',
        'meu', 'minha', 'meus', 'minhas',
        'teu', 'tua', 'teus', 'tuas',
        'seu', 'sua', 'seus', 'suas',
        'nosso', 'nossa', 'nossos', 'nossas',
        'vosso', 'vossa', 'vossos', 'vossas',
        'ser', 'estar', 'ter', 'haver', 'fazer',
        'foi', 'é', 'são', 'era', 'foram', 'seja', 'sejam',
        'está', 'estão', 'estava', 'estavam', 'esteja', 'estejam',
        'tem', 'têm', 'tinha', 'tinham', 'tenha', 'tenham',
        'há', 'houve', 'houveram', 'haja', 'hajam',
        'faz', 'fazem', 'fazia', 'faziam', 'faça', 'façam',
        'me', 'te', 'se', 'lhe', 'lhes', 'nos', 'vos',
        'mim', 'ti', 'si', 'nós', 'vós', 'eles', 'elas',
        'eu', 'tu', 'ele', 'ela', 'você', 'vocês',
        'onde', 'quando', 'como', 'porque', 'porquê', 'por que',
        'muito', 'muita', 'muitos', 'muitas', 'pouco', 'pouca', 'poucos', 'poucas',
        'mais', 'menos', 'tão', 'tal', 'tanto', 'tanta', 'tantos', 'tantas',
        'todo', 'toda', 'todos', 'todas', 'outro', 'outra', 'outros', 'outras',
        'mesmo', 'mesma', 'mesmos', 'mesmas',
        'também', 'ainda', 'já', 'nunca', 'sempre', 'jamais',
        'sim', 'não', 'nem',
        'apenas', 'somente', 'só',
    ];

    /**
     * Normalize and tokenize text into words.
     *
     * @param string|null $text
     * @return array
     */
    public function tokenize(?string $text): array
    {
        if (empty($text)) {
            return [];
        }

        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');

        // Remove punctuation and special characters, keep only letters and spaces
        $text = preg_replace('/[^\p{L}\s]/u', ' ', $text);

        // Split into words
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        return $words;
    }

    /**
     * Remove stop words from an array of words.
     *
     * @param array $words
     * @return array
     */
    public function removeStopWords(array $words): array
    {
        return array_filter($words, function ($word) {
            return !in_array($word, self::STOP_WORDS) && mb_strlen($word, 'UTF-8') > 2;
        });
    }

    /**
     * Extract significant keywords from text.
     *
     * @param string|null $text
     * @return array
     */
    public function extractKeywords(?string $text): array
    {
        $words = $this->tokenize($text);
        $keywords = $this->removeStopWords($words);

        // Return unique keywords
        return array_unique(array_values($keywords));
    }

    /**
     * Calculate Jaccard similarity between two sets of keywords.
     * Returns a value between 0.0 (no similarity) and 1.0 (identical).
     *
     * @param array $keywords1
     * @param array $keywords2
     * @return float
     */
    public function calculateSimilarity(array $keywords1, array $keywords2): float
    {
        if (empty($keywords1) || empty($keywords2)) {
            return 0.0;
        }

        $set1 = array_unique($keywords1);
        $set2 = array_unique($keywords2);

        // Calculate intersection (common keywords)
        $intersection = array_intersect($set1, $set2);
        $intersectionCount = count($intersection);

        // Calculate union (all unique keywords)
        $union = array_unique(array_merge($set1, $set2));
        $unionCount = count($union);

        if ($unionCount === 0) {
            return 0.0;
        }

        // Jaccard similarity coefficient
        return $intersectionCount / $unionCount;
    }

    /**
     * Calculate similarity between two text strings.
     *
     * @param string|null $text1
     * @param string|null $text2
     * @return float
     */
    public function calculateTextSimilarity(?string $text1, ?string $text2): float
    {
        $keywords1 = $this->extractKeywords($text1);
        $keywords2 = $this->extractKeywords($text2);

        return $this->calculateSimilarity($keywords1, $keywords2);
    }
}
