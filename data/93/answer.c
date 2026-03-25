#include <stdio.h>
int main() {
    int n;
    scanf("%d", &n);
    int k = 1;
    while (k * (k + 1) / 2 < n) {
        k++;
    }
    printf("%d\n", k * (k + 1) / 2);
    return 0;
}
