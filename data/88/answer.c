#include <stdio.h>
int main(){
    int n, arr[100];
    scanf("%d", &n);
    for(int i=0; i<n; i++) scanf("%d", &arr[i]);
    printf("%d", arr[n-1]);
    for(int i=0; i<n-1; i++) printf(" %d", arr[i]);
    printf("\n");
    return 0;
}
