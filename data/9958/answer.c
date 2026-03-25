#include <stdio.h>
int main() {
    int a;
    scanf("%d", &a);
    if (a > 0) printf("plus\n");
    else if (a < 0) printf("minus\n");
    else printf("zero\n");
    if (a % 2 == 0) printf("even\n");
    else printf("odd\n");
    return 0;
}
