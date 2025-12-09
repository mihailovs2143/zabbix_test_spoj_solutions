<?php

declare(strict_types=1);

class InputReader
{
    /** @var resource */
    private $handle;

    /**
     * Initialize input reader with STDIN
     */
    public function __construct()
    {
        $this->handle = STDIN;
    }

    /**
     * Read a single line and trim whitespace
     *
     * @return string The trimmed line
     */
    public function readLine(): string
    {
        $line = fgets($this->handle);

        return $line !== false ? trim($line) : '';
    }

    /**
     * Read a single integer
     *
     * @return int The integer value
     */
    public function readInt(): int
    {
        return (int) $this->readLine();
    }

    /**
     * Read a single float/double
     *
     * @return float The float value
     */
    public function readFloat(): float
    {
        return (float) $this->readLine();
    }

    /**
     * Read a line and split into array of integers
     *
     * @return int[] Array of integers
     */
    public function readIntArray(): array
    {
        $line = $this->readLine();
        if ($line === '') {
            return [];
        }

        return array_map('intval', explode(' ', $line));
    }

    /**
     * Read a line and split into array of floats
     *
     * @return float[] Array of floats
     */
    public function readFloatArray(): array
    {
        $line = $this->readLine();
        if ($line === '') {
            return [];
        }

        return array_map('floatval', explode(' ', $line));
    }

    /**
     * Read a line and split into array of strings
     *
     * @return string[] Array of strings
     */
    public function readStringArray(): array
    {
        $line = $this->readLine();
        if ($line === '') {
            return [];
        }

        return explode(' ', $line);
    }

    /**
     * Read entire remaining input as string
     *
     * @return string All remaining input
     */
    public function readAll(): string
    {
        return stream_get_contents($this->handle);
    }

    /**
     * Check if there is more input to read
     *
     * @return bool True if more input available
     */
    public function hasMoreInput(): bool
    {
        return !feof($this->handle);
    }
}

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

            if (!preg_match('/^[a-z]+$/', $word)) {
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

        if (!$this->isConnected()) {
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

            if (!isset($this->graph[$first])) {
                $this->graph[$first] = [];
            }
            $this->graph[$first][] = $last;

            if (!isset($this->outDegree[$first])) {
                $this->outDegree[$first] = 0;
            }
            if (!isset($this->inDegree[$last])) {
                $this->inDegree[$last] = 0;
            }
            
            $this->outDegree[$first]++;
            $this->inDegree[$last]++;
        }

        foreach (array_keys($this->vertices) as $vertex) {
            if (!isset($this->inDegree[$vertex])) {
                $this->inDegree[$vertex] = 0;
            }
            if (!isset($this->outDegree[$vertex])) {
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
                && !isset($visited[$vertex])) {
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
                if (!isset($undirected[$from])) {
                    $undirected[$from] = [];
                }
                if (!isset($undirected[$to])) {
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
                if (!isset($visited[$neighbor])) {
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

if (php_sapi_name() === 'cli' && !defined('SPOJ_CLI_MODE') && !defined('PHPUNIT_COMPOSER_INSTALL')) {
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
