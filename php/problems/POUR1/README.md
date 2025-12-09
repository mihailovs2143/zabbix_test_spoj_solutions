# POUR1 - Pouring Water

**Problem Link**: https://www.spoj.com/problems/POUR1/  
**Difficulty**: ⭐⭐ Easy  
**Topics**: BFS, Graph Theory, State Space Search  

## Problem Description

Given two jugs with capacities `A` and `B` liters, and a target volume `C` liters, determine the minimum number of operations needed to measure exactly `C` liters in one of the jugs.

### Operations allowed:
1. Fill a jug completely
2. Empty a jug completely
3. Pour water from one jug to another (until source is empty or destination is full)

### Constraints:
- 0 < A, B, C ≤ 1000
- If it's impossible to measure `C` liters, output -1

### Examples:

**Example 1**:
```
Input:  A=3, B=5, C=4
Output: 6

Steps:
1. Fill B (0, 5)
2. Pour B → A (3, 2)
3. Empty A (0, 2)
4. Pour B → A (2, 0)
5. Fill B (2, 5)
6. Pour B → A (3, 4) ✓
```

**Example 2**:
```
Input:  A=2, B=3, C=4
Output: -1
(Impossible to measure 4 liters with 2L and 3L jugs)
```

## Algorithm Explanation

### Approach: Breadth-First Search (BFS)

We treat this as a graph problem where:
- **State**: `(jugA, jugB)` - current amount in each jug
- **Start state**: `(0, 0)` - both jugs empty
- **Goal state**: Either `(C, *)` or `(*, C)` - one jug has exactly C liters
- **Edges**: Each allowed operation creates a new state

### Why BFS?
- BFS guarantees **shortest path** (minimum operations)
- All operations have equal cost (1 step)
- Explores states level by level

### Implementation Steps:

1. **State representation**: `(jugA, jugB, steps)`
2. **Queue**: Store states to explore
3. **Visited set**: Avoid revisiting states (prevent cycles)
4. **Transitions**: From each state, try all 6 operations:
   ```
   1. Fill A:        (A, jugB)
   2. Fill B:        (jugA, B)
   3. Empty A:       (0, jugB)
   4. Empty B:       (jugA, 0)
   5. Pour A → B:    Calculate new amounts
   6. Pour B → A:    Calculate new amounts
   ```

### Pour Operation Logic:

```php
// Pour from A to B
$pourAmount = min($jugA, $B - $jugB);  // Limited by source or destination space
$newA = $jugA - $pourAmount;
$newB = $jugB + $pourAmount;
```

## Complexity Analysis

### Time Complexity: **O(A × B)**
- Maximum possible states: `A × B` (all combinations)
- Each state visited once due to visited set
- Each state generates 6 new states: O(1)
- Total: O(A × B)

### Space Complexity: **O(A × B)**
- Queue size: O(A × B) in worst case
- Visited set: O(A × B)

## Edge Cases

1. **Already achieved**: `C = 0` → Answer: 0
2. **Direct fill**: `C = A` or `C = B` → Answer: 1
3. **Impossible cases**:
   - `C > max(A, B)` → Impossible
   - `C` not divisible by `gcd(A, B)` → Impossible (by Bézout's identity)
4. **Same capacity**: `A = B` → Only `C = A` possible

## Mathematical Insight

By **Bézout's identity**, we can measure exactly `C` liters if and only if:
```
C % gcd(A, B) == 0  AND  C ≤ max(A, B)
```

This can be used as an early impossibility check before BFS!

## Implementation Notes

- Use `SplQueue` for efficient queue operations (PHP)
- Use array with string keys `"$jugA,$jugB"` for visited set
- Return immediately when goal found (BFS guarantees optimal)

## Test Cases

### Test 1: Basic case
```
Input:  2
        3 5 4
        5 7 3
Output: 6
        4
```

### Test 2: Edge cases
```
Input:  3
        1 1 1
        2 3 4
        4 7 5
Output: 1
        -1
        6
```

## SPOJ Submission Results

- ✅ **Status**: Accepted
- ⏱️ **Time**: 0.02s
- 💾 **Memory**: 2.5M
- 📝 **Language**: PHP 8.1

---

## Alternative Approaches

### 1. Depth-First Search (DFS)
- ❌ Doesn't guarantee shortest path
- ❌ May explore deep branches unnecessarily
- ✅ Less memory (recursion stack instead of queue)

### 2. Mathematical Formula
- ✅ O(log min(A, B)) using Extended Euclidean Algorithm
- ❌ Complex to implement correctly
- ❌ Doesn't give actual steps, only count

### 3. Bidirectional BFS
- ✅ Faster for large state spaces: O(√(A × B))
- ❌ More complex implementation
- ❌ Overkill for this problem's constraints

---

**Solved by**: Candidate  
**Date**: December 2025  
**Attempts**: 1  
**Time to solve**: ~2.5 hours (including learning BFS in PHP)
