#include <stdio.h>
void stars(int n) {
    if (n <= 0) return;
    stars(n - 1);
    printf("*");
}
int main(void) {
    int n;
    scanf("%d", &n);
    stars(n);
    printf("\n");
    return 0;
}
