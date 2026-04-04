#include <stdio.h>
int main() {
    int n, original, reversed = 0;
    scanf("%d", &n);
    original = n;
    while (n > 0) {
        reversed = reversed * 10 + n % 10;
        n /= 10;
    }
    if (original == reversed) printf("Yes\n");
    else printf("No\n");
    return 0;
}
