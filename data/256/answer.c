#include <stdio.h>
int main(void) {
    int n;
    scanf("%d", &n);
    if (n < 0) printf("negative\n");
    else if (n == 0) printf("zero\n");
    else printf("positive\n");
    return 0;
}
