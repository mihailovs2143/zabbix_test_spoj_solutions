# SPOJ Solutions for Zabbix Developer Interview

**Candidate for PHP Developer position**  
**December 2025**

## 📋 Problems Solved

Complete solutions for 10 competitive programming problems demonstrating algorithmic thinking and clean code practices.

| Problem | Algorithm | Lines | Tests |
|---------|-----------|-----------|-------|-------|
| [POUR1](https://www.spoj.com/problems/POUR1/) | BFS | 234 | 16 |
| [ARITH](https://www.spoj.com/problems/ARITH/) | String Arithmetic | 321 | 16 |
| [CHOCOLA](https://www.spoj.com/problems/CHOCOLA/) | Greedy | 194 | 16 |
| [AGGRCOW](https://www.spoj.com/problems/AGGRCOW/) | Binary Search | 163 | 16 |
| [BEADS](https://www.spoj.com/problems/BEADS/) | Booth's Algorithm | 177 | 16 |
| [CMPLS](https://www.spoj.com/problems/CMPLS/) | Finite Differences | 229 | 18 |
| [PERMUT1](https://www.spoj.com/problems/PERMUT1/) | Dynamic Programming | 140 | 21 |
| [TOE1](https://www.spoj.com/problems/TOE1/) | Game Theory | 164 | 22 |
| [TRT](https://www.spoj.com/problems/TRT/) | Interval DP | 152 | 21 |
| [WORDS1](https://www.spoj.com/problems/WORDS1/) | Eulerian Path | 271 | 19 |

**Statistics**: 10 problems, ~2000 lines of code, 180 unit tests (100% passing)

## 🏗️ Project Structure

```
zabbix-spoj-solutions/
├── README.md                    # Documentation
├── composer.json                # Dependencies
├── phpunit.xml                  # Test configuration
├── spoj                         # Interactive CLI runner
│
├── php/
│   ├── problems/
│   │   ├── POUR1/              # Each problem in its own folder
│   │   │   ├── solution.php    # Main solution
│   │   │   ├── test_cases/     # Test inputs/outputs
│   │   │   └── README.md       # Algorithm explanation
│   │   ├── ARITH/
│   │   └── ...
│   │
│   ├── src/Common/             # Reusable utilities
│   │   ├── InputReader.php     # Input parsing
│   │   └── ProblemDescriptions.php  # CLI metadata
│   │
│   └── tests/                  # PHPUnit test suite
│       └── Problems/
│
└── compiled_for_spoj_upload/   # Standalone submission files
```

## 🛠️ Technology Stack

- **PHP**: 8.3+ with strict types
- **Testing**: PHPUnit 10.5
- **Standards**: PSR-12, comprehensive validation
- **Tools**: Composer, interactive CLI

## 🚀 Quick Start

### Install Dependencies
```bash
composer install
```

### Run Individual Solution
```bash
php php/problems/POUR1/solution.php < php/problems/POUR1/test_cases/input1.txt
```

### Run All Tests
```bash
composer test
```

### Interactive CLI Runner
```bash
php spoj
```

The interactive CLI provides:
- Guided input with validation
- Multiple input modes (manual, file, JSON)
- Problem selection menu
- Automatic test execution

## 📊 Features

### Clean Architecture
- Each problem isolated in separate module
- Reusable components extracted to `src/Common`
- Clear separation of concerns

### Type Safety
- `declare(strict_types=1)` in all PHP files
- Full type hints for parameters and returns
- PHPDoc annotations for complex types

### Input Validation
- Comprehensive constraint checking
- Meaningful error messages
- Strict validation in CLI mode

### Testing
- 180 unit tests with PHPUnit
- Edge cases and boundary conditions
- 100% passing test suite

### Documentation
- Algorithm explanations for each problem
- Time/space complexity analysis
- Implementation notes

## 🎓 Algorithms Implemented

### Graph Theory
- **BFS** (POUR1): State space exploration for liquid pouring
- **Eulerian Path** (WORDS1): Word chain validation using graph theory

### Dynamic Programming
- **Interval DP** (TRT): Optimal treat selection strategy
- **Permutation DP** (PERMUT1): Counting inversions in permutations

### Greedy Algorithms
- **Optimization** (CHOCOLA): Chocolate breaking with minimum cost

### Search Algorithms
- **Binary Search** (AGGRCOW): Search on answer space for cow placement

### String Algorithms
- **Booth's Algorithm** (BEADS): Lexicographically minimal rotation
- **Finite Differences** (CMPLS): Sequence pattern completion

### Game Theory
- **State Validation** (TOE1): Tic-tac-toe board validation

## 📖 CLI Usage Guide

### Interactive Mode

The CLI runner (`php spoj`) provides a guided interface:

```bash
$ php spoj

=== ГЛАВНОЕ МЕНЮ ===

Действия:
  1. Выбрать и запустить задачу
  2. Запустить все тесты (PHPUnit)
  
Настройки:
  0. Режим ввода [manual/file/json]

Навигация:
  m. Показать меню
  q. Выход
```

### Guided Input Example

When running a problem in manual mode:

```
=== Запуск: AGGRCOW ===

📝 Ручной ввод данных
Задача: Aggressive Cows
Расставить коров в стойлах так, чтобы минимальное расстояние было максимальным

Пример ввода:
1
5 3
1
2
8
4
9

Режим: строгий ввод с валидацией

→ Количество тест-кейсов: 1
→ N (стойл) и C (коров) через пробел: 5 3
→ Введите 5 позиций стойл (по одной на строку):
   Стойло 1/5: 1
   Стойло 2/5: 2
   Стойло 3/5: 8
   Стойло 4/5: 4
   Стойло 5/5: 9

=== РЕЗУЛЬТАТ ===
3
```

### Input Validation

The CLI validates all inputs:

**Test case count validation:**
```
→ Количество тест-кейсов: [empty]
✗ Ошибка: необходимо ввести число!

→ Количество тест-кейсов: 0
✗ Ошибка: количество тест-кейсов должно быть больше 0!
```

**Parameter validation:**
```
→ N (стойл) и C (коров) через пробел: 5
✗ Ошибка: необходимо ввести N и C через пробел!

→ N (стойл) и C (коров) через пробел: 5 3 ✓
```

**Numeric validation:**
```
→ Стойло 1/5: abc
✗ Ошибка: необходимо ввести число!

→ Стойло 1/5: 1 ✓
```

### Problem-Specific Guides

Each problem includes guided input prompts:

- **AGGRCOW**: Test count → N and C → N stall positions
- **ARITH**: Test count → Arithmetic expressions
- **BEADS**: Test count → String length → Bead string
- **CHOCOLA**: Test count → M and N → Cut costs
- **CMPLS**: Test count → Sequence size → Values
- **PERMUT1**: Test count → N and K pairs
- **POUR1**: Test count → A, B, C triplets
- **TOE1**: Test count → 3 board lines
- **TRT**: Treat count → Values
- **WORDS1**: Test count → Word count → Words

## 🔧 Development

### Running Tests
```bash
# All tests
composer test

# Specific problem
./vendor/bin/phpunit --filter POUR1Test

# With coverage (requires xdebug)
composer test -- --coverage-text
```

### Code Style
```bash
# Check style
composer cs-check

# Fix style
composer cs-fix
```

### SPOJ Submission

Standalone files for SPOJ upload are pre-generated in `compiled_for_spoj_upload/`:

```bash
ls compiled_for_spoj_upload/
# spoj_POUR1.php  spoj_ARITH.php  spoj_CHOCOLA.php  ...
```

Each file contains:
- Embedded `InputReader` class
- Complete solution code
- Ready for direct submission to SPOJ

## 📚 Problem Details

### POUR1 - Water Pouring
**Algorithm**: BFS state space search  
**Complexity**: O(A × B)  
**Key Insight**: Model as graph where states are (a, b) amounts in jugs

### ARITH - Simple Arithmetics
**Algorithm**: String-based big integer arithmetic  
**Complexity**: O(n × m) for multiplication  
**Key Insight**: Handle numbers as strings, implement grade-school algorithms

### CHOCOLA - Chocolate Breaking
**Algorithm**: Greedy with sorting  
**Complexity**: O(n log n)  
**Key Insight**: Always make most expensive cut first

### AGGRCOW - Aggressive Cows
**Algorithm**: Binary search on answer  
**Complexity**: O(n log n + n log(max_pos))  
**Key Insight**: If distance d works, all smaller distances work too

### BEADS - Glass Beads
**Algorithm**: Booth's minimal rotation  
**Complexity**: O(n)  
**Key Insight**: Use failure function to skip comparisons

### CMPLS - Complete the Sequence
**Algorithm**: Finite differences  
**Complexity**: O(S²)  
**Key Insight**: Polynomial degree = number of differencing levels

### PERMUT1 - Permutations
**Algorithm**: DP counting inversions  
**Complexity**: O(N × K × N)  
**Key Insight**: Track inversions when building permutation

### TOE1 - Tic-Tac-Toe
**Algorithm**: Game state validation  
**Complexity**: O(1)  
**Key Insight**: Check move counts and win conditions consistency

### TRT - Treats for the Cows
**Algorithm**: Interval dynamic programming  
**Complexity**: O(N²)  
**Key Insight**: Subproblems are contiguous intervals

### WORDS1 - Word Puzzle
**Algorithm**: Eulerian path detection  
**Complexity**: O(N × L + E)  
**Key Insight**: Words form edges in directed graph of letters

## 🔗 Resources

- [SPOJ Platform](https://www.spoj.com/)
- [Zabbix GitHub](https://github.com/zabbix/zabbix)
- [PHP Manual](https://www.php.net/manual/)
- [PHPUnit Documentation](https://phpunit.de/)
- [Other math resources] (...)
---

**Project completed**: December 2025
