#include <stdio.h>
int main() {
    int n, sum = 0, day = 0;
    scanf("%d", &n);
    while (sum < n) {
        day++;
        sum += day;
    }
    printf("%d\n", day);
    return 0;
}
