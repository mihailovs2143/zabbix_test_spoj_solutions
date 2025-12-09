<?php

declare(strict_types=1);

/**
 * WORDS1 - Play on Words
 * Graph Theory - Eulerian Path in Directed Graph
 *
 * @see https://www.spoj.com/problems/WORDS1/
 */

require_once __DIR__ . '/../../src/Common/InputReader.php';

use ZabbixSPOJ\Common\InputReader;

/**
 * Class WordChainValidator
 *
 * Determines if words can be arranged in a chain where each word starts
 * with the last letter of the previous word (Eulerian Path problem)
 *
 * Algorithm:
 * 1. Build directed graph: edge from first_letter to last_letter for each word
 * 2. Check if Eulerian path exists:
 *    a) All vertices must be in one connected component
 *    b) In-degree and out-degree must satisfy Eulerian path conditions:
 *       - Either all vertices have in-degree = out-degree (Eulerian circuit)
 *       - OR exactly one vertex with out-degree - in-degree = 1 (start)
 *         and one vertex with in-degree - out-degree = 1 (end),
 *         all others with in-degree = out-degree (Eulerian path)
 *
 * Example: ["acm", "malform", "mouse"]
 * Graph edges: a→m, m→m, m→e
 * Path exists: a→m→m→e ✓
 */
class WordChainValidator
{
    private array $words;
    private array $inDegree;
    private array $outDegree;
    private array $graph;
    private array $vertices;

    /**
     * @param array $words Array of words
     * @throws InvalidArgumentException
     */
    public function __construct(array $words)
    {
        $this->validateInput($words);
        $this->words = $words;
        $this->inDegree = [];
        $this->outDegree = [];
        $this->graph = [];
        $this->vertices = [];
    }

    /**
     * Validate input constraints
     */
    private function validateInput(array $words): void
    {
        if (empty($words)) {
            throw new InvalidArgumentException("Words array cannot be empty");
        }

        foreach ($words as $word) {
            $len = strlen($word);
            if ($len < 2 || $len > 1000) {
                throw new InvalidArgumentException(
                    "Word length must be between 2 and 1000, got: {$len} for word: {$word}"
                );
            }

            if (! preg_match('/^[a-z]+$/', $word)) {
                throw new InvalidArgumentException(
                    "Word must contain only lowercase letters, got: {$word}"
                );
            }
        }
    }

    /**
     * Check if words can be arranged in valid chain
     *
     * @return bool True if ordering is possible
     */
    public function solve(): bool
    {
        $this->buildGraph();

        if (! $this->isConnected()) {
            return false;
        }

        return $this->hasEulerianPath();
    }

    /**
     * Build directed graph from words
     */
    private function buildGraph(): void
    {
        foreach ($this->words as $word) {
            $first = $word[0];
            $last = $word[strlen($word) - 1];

            $this->vertices[$first] = true;
            $this->vertices[$last] = true;

            if (! isset($this->graph[$first])) {
                $this->graph[$first] = [];
            }
            $this->graph[$first][] = $last;

            if (! isset($this->outDegree[$first])) {
                $this->outDegree[$first] = 0;
            }
            if (! isset($this->inDegree[$last])) {
                $this->inDegree[$last] = 0;
            }

            $this->outDegree[$first]++;
            $this->inDegree[$last]++;
        }

        foreach (array_keys($this->vertices) as $vertex) {
            if (! isset($this->inDegree[$vertex])) {
                $this->inDegree[$vertex] = 0;
            }
            if (! isset($this->outDegree[$vertex])) {
                $this->outDegree[$vertex] = 0;
            }
        }
    }

    /**
     * Check if graph is weakly connected (treat as undirected)
     */
    private function isConnected(): bool
    {
        if (empty($this->vertices)) {
            return true;
        }

        $undirected = $this->buildUndirectedGraph();
        $startVertex = array_key_first($this->vertices);
        $visited = [];

        $this->dfs($startVertex, $visited, $undirected);

        foreach (array_keys($this->vertices) as $vertex) {
            if (($this->inDegree[$vertex] > 0 || $this->outDegree[$vertex] > 0)
                && ! isset($visited[$vertex])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Build undirected graph for connectivity check
     */
    private function buildUndirectedGraph(): array
    {
        $undirected = [];

        foreach ($this->graph as $from => $neighbors) {
            foreach ($neighbors as $to) {
                if (! isset($undirected[$from])) {
                    $undirected[$from] = [];
                }
                if (! isset($undirected[$to])) {
                    $undirected[$to] = [];
                }

                $undirected[$from][] = $to;
                $undirected[$to][] = $from;
            }
        }

        return $undirected;
    }

    /**
     * DFS traversal
     */
    private function dfs(string $vertex, array &$visited, array $graph): void
    {
        $visited[$vertex] = true;

        if (isset($graph[$vertex])) {
            foreach ($graph[$vertex] as $neighbor) {
                if (! isset($visited[$neighbor])) {
                    $this->dfs($neighbor, $visited, $graph);
                }
            }
        }
    }

    /**
     * Check Eulerian path conditions
     */
    private function hasEulerianPath(): bool
    {
        $startVertices = 0;
        $endVertices = 0;

        foreach (array_keys($this->vertices) as $vertex) {
            $diff = $this->outDegree[$vertex] - $this->inDegree[$vertex];

            if ($diff == 1) {
                $startVertices++;
            } elseif ($diff == -1) {
                $endVertices++;
            } elseif ($diff != 0) {
                return false;
            }
        }

        return ($startVertices == 0 && $endVertices == 0) ||
               ($startVertices == 1 && $endVertices == 1);
    }
}

// ============================================
// Main execution for SPOJ submission
// ============================================

if (php_sapi_name() === 'cli' && ! defined('SPOJ_CLI_MODE') && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
    $reader = new InputReader();
    $testCases = $reader->readInt();

    if ($testCases < 1 || $testCases > 500) {
        fwrite(STDERR, "Error: Number of treats must be between 1 and 500, got: $testCases\n");
        exit(1);
    }

    for ($t = 0; $t < $testCases; $t++) {
        $n = $reader->readInt();
        if ($n < 1 || $n > 100_000) {
            fwrite(STDERR, "Error: Number of words must be between 1 and 100_000, got: $n\n");
            exit(1);
        }

        $words = [];
        for ($i = 0; $i < $n; $i++) {
            $line = $reader->readLine();
            if ($line === null || $line === false) {
                fwrite(STDERR, "Error: Expected word on line " . ($i + 1) . " but got nothing\n");
                exit(1);
            }
            $words[] = trim($line);
        }

        try {
            $validator = new WordChainValidator($words);

            if ($validator->solve()) {
                echo "Ordering is possible.\n";
            } else {
                echo "The door cannot be opened.\n";
            }
        } catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Error in test case " . ($t + 1) . ": " . $e->getMessage() . "\n");
            exit(1);
        }
    }
}
