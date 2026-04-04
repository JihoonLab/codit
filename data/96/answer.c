#include <stdio.h>
int main() {
    int n, arr[100], res[100], cnt = 0;
    scanf("%d", &n);
    for (int i = 0; i < n; i++) scanf("%d", &arr[i]);
    for (int i = 0; i < n; i++) {
        int dup = 0;
        for (int j = 0; j < cnt; j++) {
            if (res[j] == arr[i]) { dup = 1; break; }
        }
        if (!dup) res[cnt++] = arr[i];
    }
    for (int i = 0; i < cnt; i++) {
        if (i > 0) printf(" ");
        printf("%d", res[i]);
    }
    printf("\n");
    return 0;
}
