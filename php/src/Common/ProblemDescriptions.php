<?php

declare(strict_types=1);

/**
 * Input prompts and descriptions for SPOJ problems
 */
class ProblemDescriptions
{
    /**
     * Get problem description and input prompts
     */
    public static function getDescription(string $problem): array
    {
        $descriptions = [
            'AGGRCOW' => [
                'name' => 'Aggressive Cows',
                'description' => 'Расставить коров в стойлах так, чтобы минимальное расстояние было максимальным',
                'prompts' => [
                    'Количество тест-кейсов',
                    'N (стойл) и C (коров) через пробел',
                    'Позиции стойл (по одной на строку, N строк)',
                ],
                'example' => "1\n5 3\n1\n2\n8\n4\n9",
            ],

            'ARITH' => [
                'name' => 'Simple Arithmetics',
                'description' => 'Арифметика для больших чисел (сложение, вычитание, умножение)',
                'prompts' => [
                    'Количество тест-кейсов',
                    'Выражение (число операция число, например: 12345+54321)',
                ],
                'example' => "2\n12345+54321\n123*456",
            ],

            'BEADS' => [
                'name' => 'Glass Beads',
                'description' => 'Найти лексикографически минимальную ротацию строки',
                'prompts' => [
                    'Количество тест-кейсов',
                    'Длина строки',
                    'Строка из lowercase букв',
                ],
                'example' => "2\n3\ncab\n6\nbaabaa",
            ],

            'CHOCOLA' => [
                'name' => 'Chocolate',
                'description' => 'Разрезать шоколадку с минимальной стоимостью',
                'prompts' => [
                    'Количество тест-кейсов',
                    'M (высота-1) и N (ширина-1) через пробел',
                    'M чисел - стоимость горизонтальных разрезов',
                    'N чисел - стоимость вертикальных разрезов',
                ],
                'example' => "1\n2 2\n2 1\n3 4",
            ],

            'CMPLS' => [
                'name' => 'Complete the Sequence',
                'description' => 'Найти следующие элементы последовательности',
                'prompts' => [
                    'Количество тест-кейсов',
                    'S - количество известных элементов',
                    'S чисел через пробел - известные элементы',
                ],
                'example' => "3\n5\n1 2 3 4 5\n4\n1 4 9 16\n6\n269 441 624 818 1023 1239",
            ],

            'PERMUT1' => [
                'name' => 'Permutations',
                'description' => 'Количество перестановок длины N с K инверсиями',
                'prompts' => [
                    'Количество тест-кейсов',
                    'N (длина) и K (инверсии) через пробел',
                ],
                'example' => "3\n4 1\n5 3\n6 10",
            ],

            'POUR1' => [
                'name' => 'Pouring Water',
                'description' => 'Получить C литров воды используя два кувшина A и B литров',
                'prompts' => [
                    'Количество тест-кейсов',
                    'A B C через пробел (ёмкости и целевой объём)',
                ],
                'example' => "2\n5 2 3\n2 3 4",
            ],

            'TOE1' => [
                'name' => 'Tic-Tac-Toe I',
                'description' => 'Проверить корректность позиции в крестики-нолики',
                'prompts' => [
                    'Количество тест-кейсов',
                    '3 строки по 3 символа (X, O или .)',
                ],
                'example' => "2\nXXX\nOOO\n...\nXOX\nOXO\nXOX",
            ],

            'TRT' => [
                'name' => 'Treats for the Cows',
                'description' => 'Максимизировать выручку от продажи лакомств коровам',
                'prompts' => [
                    'N - количество лакомств',
                    'N чисел - ценности лакомств',
                ],
                'example' => "5\n1 3 1 5 2",
            ],

            'WORDS1' => [
                'name' => 'Play on Words',
                'description' => 'Можно ли составить цепочку слов (конец→начало)',
                'prompts' => [
                    'Количество тест-кейсов',
                    'N - количество слов',
                    'N слов (по одному на строку)',
                ],
                'example' => "2\n3\nacm\nmalform\nmouse\n2\nok\nkak",
            ],
        ];

        return $descriptions[$problem] ?? [
            'name' => $problem,
            'description' => 'Описание отсутствует',
            'prompts' => ['Введите данные'],
            'example' => '',
        ];
    }

    /**
     * Get input handler for strict validation
     * Returns a callable that guides user through input step by step
     */
    public static function getInputHandler(string $problem): ?callable
    {
        $handlers = [
            'AGGRCOW' => function () {
                $input = [];

                // 1. Количество тест-кейсов
                echo COLOR_CYAN . "→ Количество тест-кейсов: " . COLOR_RESET;
                $line = trim(fgets(STDIN));

                if (empty($line) || ! is_numeric($line)) {
                    echo COLOR_RED . "✗ Ошибка: необходимо ввести число!\n" . COLOR_RESET;

                    return '';
                }

                $t = (int) $line;
                if ($t < 1) {
                    echo COLOR_RED . "✗ Ошибка: количество тест-кейсов должно быть больше 0!\n" . COLOR_RESET;

                    return '';
                }

                $input[] = $t;

                for ($test = 0; $test < $t; $test++) {
                    if ($t > 1) {
                        echo COLOR_YELLOW . "\nТест-кейс " . ($test + 1) . " из $t:\n" . COLOR_RESET;
                    }

                    // 2. N и C
                    echo COLOR_CYAN . "→ N (стойл) и C (коров) через пробел: " . COLOR_RESET;
                    $line = trim(fgets(STDIN));

                    // Валидация ввода
                    if (empty($line)) {
                        echo COLOR_RED . "✗ Ошибка: необходимо ввести два числа через пробел!\n" . COLOR_RESET;

                        return '';
                    }

                    $parts = preg_split('/\s+/', $line);
                    if (count($parts) < 2) {
                        echo COLOR_RED . "✗ Ошибка: необходимо ввести N и C через пробел!\n" . COLOR_RESET;

                        return '';
                    }

                    $n = (int) $parts[0];
                    $c = (int) $parts[1];
                    $input[] = $line;

                    // 3. N позиций стойл
                    if ($n < 2) {
                        echo COLOR_RED . "✗ Ошибка: N должно быть больше 1!\n" . COLOR_RESET;

                        return '';
                    }

                    echo COLOR_CYAN . "→ Введите $n позиций стойл (по одной на строку):\n" . COLOR_RESET;
                    for ($i = 0; $i < $n; $i++) {
                        echo COLOR_GRAY . "   Стойло " . ($i + 1) . "/$n: " . COLOR_RESET;
                        $pos = trim(fgets(STDIN));

                        if (empty($pos) || ! is_numeric($pos)) {
                            echo COLOR_RED . "✗ Ошибка: необходимо ввести число!\n" . COLOR_RESET;

                            return '';
                        }

                        $input[] = $pos;
                    }
                }

                return implode("\n", $input);
            },

            'ARITH' => function () {
                $input = [];

                echo COLOR_CYAN . "→ Количество тест-кейсов: " . COLOR_RESET;
                $t = (int) trim(fgets(STDIN));
                $input[] = $t;

                for ($i = 0; $i < $t; $i++) {
                    if ($t > 1) {
                        echo COLOR_YELLOW . "\nВыражение " . ($i + 1) . " из $t:\n" . COLOR_RESET;
                    }
                    echo COLOR_CYAN . "→ Выражение (например, 123+456): " . COLOR_RESET;
                    $input[] = trim(fgets(STDIN));
                }

                return implode("\n", $input);
            },

            'BEADS' => function () {
                $input = [];

                echo COLOR_CYAN . "→ Количество тест-кейсов: " . COLOR_RESET;
                $t = (int) trim(fgets(STDIN));
                $input[] = $t;

                for ($i = 0; $i < $t; $i++) {
                    if ($t > 1) {
                        echo COLOR_YELLOW . "\nТест-кейс " . ($i + 1) . " из $t:\n" . COLOR_RESET;
                    }
                    echo COLOR_CYAN . "→ Длина строки: " . COLOR_RESET;
                    $input[] = trim(fgets(STDIN));
                    echo COLOR_CYAN . "→ Строка: " . COLOR_RESET;
                    $input[] = trim(fgets(STDIN));
                }

                return implode("\n", $input);
            },

            'CHOCOLA' => function () {
                $input = [];

                echo COLOR_CYAN . "→ Количество тест-кейсов: " . COLOR_RESET;
                $t = (int) trim(fgets(STDIN));
                $input[] = $t;

                for ($test = 0; $test < $t; $test++) {
                    if ($t > 1) {
                        echo COLOR_YELLOW . "\nТест-кейс " . ($test + 1) . " из $t:\n" . COLOR_RESET;
                    }

                    echo COLOR_CYAN . "→ M (горизонтальных разрезов) и N (вертикальных) через пробел: " . COLOR_RESET;
                    $line = trim(fgets(STDIN));

                    if (empty($line)) {
                        echo COLOR_RED . "✗ Ошибка: необходимо ввести два числа через пробел!\n" . COLOR_RESET;

                        return '';
                    }

                    $parts = preg_split('/\s+/', $line);
                    if (count($parts) < 2) {
                        echo COLOR_RED . "✗ Ошибка: необходимо ввести M и N через пробел!\n" . COLOR_RESET;

                        return '';
                    }

                    $m = (int) $parts[0];
                    $n = (int) $parts[1];
                    $input[] = $line;

                    echo COLOR_CYAN . "→ $m стоимостей горизонтальных разрезов через пробел: " . COLOR_RESET;
                    $input[] = trim(fgets(STDIN));

                    echo COLOR_CYAN . "→ $n стоимостей вертикальных разрезов через пробел: " . COLOR_RESET;
                    $input[] = trim(fgets(STDIN));
                }

                return implode("\n", $input);
            },

            'CMPLS' => function () {
                $input = [];

                echo COLOR_CYAN . "→ Количество тест-кейсов: " . COLOR_RESET;
                $t = (int) trim(fgets(STDIN));
                $input[] = $t;

                for ($i = 0; $i < $t; $i++) {
                    if ($t > 1) {
                        echo COLOR_YELLOW . "\nТест-кейс " . ($i + 1) . " из $t:\n" . COLOR_RESET;
                    }
                    echo COLOR_CYAN . "→ S (размер последовательности): " . COLOR_RESET;
                    $input[] = trim(fgets(STDIN));
                    echo COLOR_CYAN . "→ S чисел через пробел: " . COLOR_RESET;
                    $input[] = trim(fgets(STDIN));
                }

                return implode("\n", $input);
            },

            'PERMUT1' => function () {
                $input = [];

                echo COLOR_CYAN . "→ Количество тест-кейсов: " . COLOR_RESET;
                $t = (int) trim(fgets(STDIN));
                $input[] = $t;

                for ($i = 0; $i < $t; $i++) {
                    if ($t > 1) {
                        echo COLOR_YELLOW . "\nТест-кейс " . ($i + 1) . " из $t:\n" . COLOR_RESET;
                    }
                    echo COLOR_CYAN . "→ N (элементов) и K (номер перестановки) через пробел: " . COLOR_RESET;
                    $line = trim(fgets(STDIN));

                    if (empty($line)) {
                        echo COLOR_RED . "✗ Ошибка: необходимо ввести два числа через пробел!\n" . COLOR_RESET;

                        return '';
                    }

                    $input[] = $line;
                }

                return implode("\n", $input);
            },

            'POUR1' => function () {
                $input = [];

                echo COLOR_CYAN . "→ Количество тест-кейсов: " . COLOR_RESET;
                $t = (int) trim(fgets(STDIN));
                $input[] = $t;

                for ($i = 0; $i < $t; $i++) {
                    if ($t > 1) {
                        echo COLOR_YELLOW . "\nТест-кейс " . ($i + 1) . " из $t:\n" . COLOR_RESET;
                    }
                    echo COLOR_CYAN . "→ A (объём первого), B (второго) и C (целевой) через пробел: " . COLOR_RESET;
                    $line = trim(fgets(STDIN));

                    if (empty($line)) {
                        echo COLOR_RED . "✗ Ошибка: необходимо ввести три числа через пробел!\n" . COLOR_RESET;

                        return '';
                    }

                    $input[] = $line;
                }

                return implode("\n", $input);
            },

            'TOE1' => function () {
                $input = [];

                echo COLOR_CYAN . "→ Количество тест-кейсов: " . COLOR_RESET;
                $t = (int) trim(fgets(STDIN));
                $input[] = $t;

                for ($test = 0; $test < $t; $test++) {
                    if ($t > 1) {
                        echo COLOR_YELLOW . "\nТест-кейс " . ($test + 1) . " из $t:\n" . COLOR_RESET;
                    }
                    echo COLOR_CYAN . "→ 3 строки поля (по 3 символа: X, O или .):\n" . COLOR_RESET;
                    for ($i = 1; $i <= 3; $i++) {
                        echo COLOR_GRAY . "   Строка $i: " . COLOR_RESET;
                        $input[] = trim(fgets(STDIN));
                    }
                }

                return implode("\n", $input);
            },

            'TRT' => function () {
                $input = [];

                echo COLOR_CYAN . "→ N (количество угощений): " . COLOR_RESET;
                $n = (int) trim(fgets(STDIN));
                $input[] = $n;

                echo COLOR_CYAN . "→ $n значений через пробел: " . COLOR_RESET;
                $input[] = trim(fgets(STDIN));

                return implode("\n", $input);
            },

            'WORDS1' => function () {
                $input = [];

                echo COLOR_CYAN . "→ Количество тест-кейсов: " . COLOR_RESET;
                $t = (int) trim(fgets(STDIN));
                $input[] = $t;

                for ($test = 0; $test < $t; $test++) {
                    if ($t > 1) {
                        echo COLOR_YELLOW . "\nТест-кейс " . ($test + 1) . " из $t:\n" . COLOR_RESET;
                    }

                    echo COLOR_CYAN . "→ N (количество слов): " . COLOR_RESET;
                    $n = (int) trim(fgets(STDIN));
                    $input[] = $n;

                    echo COLOR_CYAN . "→ Введите $n слов (по одному на строку):\n" . COLOR_RESET;
                    for ($i = 0; $i < $n; $i++) {
                        echo COLOR_GRAY . "   Слово " . ($i + 1) . "/$n: " . COLOR_RESET;
                        $input[] = trim(fgets(STDIN));
                    }
                }

                return implode("\n", $input);
            },
        ];

        return $handlers[$problem] ?? null;
    }
}
