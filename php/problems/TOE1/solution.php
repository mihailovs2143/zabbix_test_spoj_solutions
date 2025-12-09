<?php

declare(strict_types=1);

/**
 * TOE1 - Tic-Tac-Toe ( I )
 * Game State Validation - Check if grid is valid
 *
 * @see https://www.spoj.com/problems/TOE1/
 */

require_once __DIR__ . '/../../src/Common/InputReader.php';

use ZabbixSPOJ\Common\InputReader;

/**
 * Class TicTacToeSolver
 *
 * Validates whether a Tic-Tac-Toe grid configuration is possible
 * Checks move counts, win conditions, and game state consistency
 */
class TicTacToeSolver
{
    private array $firstLine;
    private array $secondLine;
    private array $thirdLine;

    /**
     * @param string $firstLine First line of the tic tac toe board
     * @param string $secondLine Second line of the tic tac toe board
     * @param string $thirdLine Third line of the tic tac toe board
     * @throws InvalidArgumentException if input violates constraints
     */
    public function __construct(string $firstLine, string $secondLine, string $thirdLine)
    {
        $this->validateInput(trim($firstLine), trim($secondLine), trim($thirdLine));

        $this->firstLine = str_split(trim($firstLine));
        $this->secondLine = str_split(trim($secondLine));
        $this->thirdLine = str_split(trim($thirdLine));
    }

    /**
     * Validate input according to problem constraints
     *
     * @param string $firstLine First line of board
     * @param string $secondLine Second line of board
     * @param string $thirdLine Third line of board
     * @throws InvalidArgumentException if constraints are violated
     */
    private function validateInput(string $firstLine, string $secondLine, string $thirdLine): void
    {
        $lines = [$firstLine, $secondLine, $thirdLine];

        foreach ($lines as $index => $line) {
            if (strlen($line) !== 3 || ! preg_match('/^[XO\.]{3}$/', $line)) {
                throw new InvalidArgumentException(
                    "Line " . ($index + 1) . " must be exactly 3 characters long and contain only 'X', 'O', or '.', got: '$line'"
                );
            }
        }
    }

    /**
     * Validate if board configuration is possible
     *
     * @return string "yes" if valid, "no" otherwise
     */
    public function solve(): string
    {
        $board = [
            $this->firstLine,
            $this->secondLine,
            $this->thirdLine,
        ];

        // Count X's and O's
        $xCount = 0;
        $oCount = 0;
        foreach ($board as $row) {
            foreach ($row as $cell) {
                if ($cell === 'X') {
                    $xCount++;
                }
                if ($cell === 'O') {
                    $oCount++;
                }
            }
        }

        // Check move count validity: X goes first, so X count must be equal to or one more than O count
        if ($xCount < $oCount || $xCount > $oCount + 1) {
            return "no";
        }

        // Check for winners
        $xWins = $this->hasWon($board, 'X');
        $oWins = $this->hasWon($board, 'O');

        // Both players cannot win
        if ($xWins && $oWins) {
            return "no";
        }

        // If X wins, it must have been X's turn (X count = O count + 1)
        // because the game should stop after X wins
        if ($xWins && $xCount !== $oCount + 1) {
            return "no";
        }

        // If O wins, it must have been O's turn (X count = O count)
        // because the game should stop after O wins
        if ($oWins && $xCount !== $oCount) {
            return "no";
        }

        // If all checks passed, the board state is valid
        return "yes";
    }

    private function hasWon(array $board, string $player): bool
    {
        // Check rows and columns
        for ($i = 0; $i < 3; $i++) {
            if (($board[$i][0] === $player && $board[$i][1] === $player && $board[$i][2] === $player) ||
                ($board[0][$i] === $player && $board[1][$i] === $player && $board[2][$i] === $player)) {
                return true;
            }
        }

        // Check diagonals
        if (($board[0][0] === $player && $board[1][1] === $player && $board[2][2] === $player) ||
            ($board[0][2] === $player && $board[1][1] === $player && $board[2][0] === $player)) {
            return true;
        }

        return false;
    }

}

// ============================================
// Main execution for SPOJ submission
// ============================================

if (php_sapi_name() === 'cli' && ! defined('SPOJ_CLI_MODE') && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
    $reader = new InputReader();
    $testCases = $reader->readInt();

    // Validate test cases count according to problem constraints
    // if ($testCases < 1 || $testCases > 100) {
    //     fwrite(STDERR, "Error: Number of test cases must be between 1 and 100, got: $testCases\n");
    //     exit(1);
    // }

    for ($t = 0; $t < $testCases; $t++) {

        // Read blank line before each test case except the first
        if ($t > 0) {
            $reader->readLine();
        }

        // Read tic tac toe board
        for ($i = 0;$i < 3;$i++) {
            ${'line'.$i} = $reader->readLine();
        }

        try {
            $solver = new TicTacToeSolver($line0, $line1, $line2);
            echo $solver->solve() . PHP_EOL;
        } catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Error in test case " . ($t + 1) . ": " . $e->getMessage() . "\n");
            exit(1);
        }

    }
}
