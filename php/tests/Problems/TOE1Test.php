<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../problems/TOE1/solution.php';

/**
 * Tests for TOE1 - Tic-Tac-Toe ( I )
 */
class TOE1Test extends TestCase
{
    /**
     * Test first example from problem statement
     */
    public function testExampleCase1Valid(): void
    {
        $solver = new TicTacToeSolver('X.O', 'OO.', 'XXX');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test second example from problem statement
     */
    public function testExampleCase2Invalid(): void
    {
        $solver = new TicTacToeSolver('O.X', 'XX.', 'OOO');
        $result = $solver->solve();

        $this->assertEquals('no', $result);
    }

    /**
     * Test empty board
     */
    public function testEmptyBoard(): void
    {
        $solver = new TicTacToeSolver('...', '...', '...');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test board with only one X (valid start)
     */
    public function testSingleX(): void
    {
        $solver = new TicTacToeSolver('X..', '...', '...');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test board with only one O (invalid - X must go first)
     */
    public function testSingleO(): void
    {
        $solver = new TicTacToeSolver('O..', '...', '...');
        $result = $solver->solve();

        $this->assertEquals('no', $result);
    }

    /**
     * Test more O's than X's (invalid)
     */
    public function testMoreOsThanXs(): void
    {
        $solver = new TicTacToeSolver('OO.', 'X..', '...');
        $result = $solver->solve();

        $this->assertEquals('no', $result);
    }

    /**
     * Test X count exceeds O count by 2 (invalid)
     */
    public function testTooManyXs(): void
    {
        $solver = new TicTacToeSolver('XXX', 'O..', '...');
        $result = $solver->solve();

        $this->assertEquals('no', $result);
    }

    /**
     * Test valid game with X winning
     */
    public function testXWinsValid(): void
    {
        $solver = new TicTacToeSolver('XXX', 'OO.', '...');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test invalid X win (X count should be O count + 1)
     */
    public function testXWinsInvalidCount(): void
    {
        // X wins but X count = O count (should be O count + 1)
        $solver = new TicTacToeSolver('XXX', 'OOO', '...');
        $result = $solver->solve();

        $this->assertEquals('no', $result);
    }

    /**
     * Test valid game with O winning
     */
    public function testOWinsValid(): void
    {
        $solver = new TicTacToeSolver('OOO', 'XX.', 'X..');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test invalid O win (O count should equal X count)
     */
    public function testOWinsInvalidCount(): void
    {
        // O wins but X count > O count (game should have stopped)
        $solver = new TicTacToeSolver('OOO', 'XXX', '...');
        $result = $solver->solve();

        $this->assertEquals('no', $result);
    }

    /**
     * Test both players winning (impossible)
     */
    public function testBothWin(): void
    {
        $solver = new TicTacToeSolver('XXX', 'OOO', '...');
        $result = $solver->solve();

        $this->assertEquals('no', $result);
    }

    /**
     * Test diagonal win for X
     */
    public function testXDiagonalWin(): void
    {
        $solver = new TicTacToeSolver('X.O', '.X.', 'O.X');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test diagonal win for O
     */
    public function testODiagonalWin(): void
    {
        // O wins diagonal, X count = O count (3 each)
        $solver = new TicTacToeSolver('O.X', 'XOX', '..O');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test column win for X
     */
    public function testXColumnWin(): void
    {
        $solver = new TicTacToeSolver('.X.', 'OX.', '.XO');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test full board with no winner
     */
    public function testFullBoardDraw(): void
    {
        // Draw: X=5, O=4, no winner
        $solver = new TicTacToeSolver('XXO', 'OXX', 'OXO');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test validation: line too short
     */
    public function testInvalidLineTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must be exactly 3 characters long');

        new TicTacToeSolver('XX', '...', '...');
    }

    /**
     * Test validation: line too long
     */
    public function testInvalidLineTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must be exactly 3 characters long');

        new TicTacToeSolver('XXXX', '...', '...');
    }

    /**
     * Test validation: invalid character
     */
    public function testInvalidCharacter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("contain only 'X', 'O', or '.'");

        new TicTacToeSolver('XAO', '...', '...');
    }

    /**
     * Test valid game in progress
     */
    public function testGameInProgress(): void
    {
        $solver = new TicTacToeSolver('XO.', 'X..', 'O..');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test anti-diagonal win for X
     */
    public function testXAntiDiagonalWin(): void
    {
        $solver = new TicTacToeSolver('O.X', '.XO', 'X..');
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }

    /**
     * Test row win for O
     */
    public function testORowWin(): void
    {
        $solver = new TicTacToeSolver('X.X', 'OOO', 'X..', );
        $result = $solver->solve();

        $this->assertEquals('yes', $result);
    }
}
