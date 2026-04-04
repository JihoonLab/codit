#include <stdio.h>
int main() {
    int n, arr[100];
    scanf("%d", &n);
    for (int i = 0; i < n; i++) scanf("%d", &arr[i]);
    int maxLen = 1, curLen = 1;
    for (int i = 1; i < n; i++) {
        if (arr[i] > arr[i-1]) {
            curLen++;
            if (curLen > maxLen) maxLen = curLen;
        } else {
            curLen = 1;
        }
    }
    printf("%d\n", maxLen);
    return 0;
}
