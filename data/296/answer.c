#include <stdio.h>
void triangle(int i, int n) {
    if (i > n) return;
    for (int j = 0; j < i; j++) printf("*");
    printf("\n");
    triangle(i + 1, n);
}
int main(void) {
    int n;
    scanf("%d", &n);
    triangle(1, n);
    return 0;
}
