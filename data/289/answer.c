#include <stdio.h>
void printN(int i, int n) {
    if (i > n) return;
    printf("%d ", i);
    printN(i + 1, n);
}
int main(void) {
    int n;
    scanf("%d", &n);
    printN(1, n);
    printf("\n");
    return 0;
}
