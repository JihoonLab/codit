#include <stdio.h>
int main() {
    int n, arr[100];
    scanf("%d", &n);
    for (int i = 0; i < n; i++) scanf("%d", &arr[i]);
    // sort desc, find second distinct
    // simple: find max, then find max excluding max
    int max1 = -1000000, max2 = -1000000;
    for (int i = 0; i < n; i++) {
        if (arr[i] > max1) { max2 = max1; max1 = arr[i]; }
        else if (arr[i] > max2 && arr[i] != max1) max2 = arr[i];
    }
    printf("%d\n", max2);
    return 0;
}
