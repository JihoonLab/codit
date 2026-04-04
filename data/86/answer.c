#include <stdio.h>
int main(){
    int n, arr[100];
    scanf("%d", &n);
    for(int i=0; i<n; i++) scanf("%d", &arr[i]);
    int mx = arr[0];
    for(int i=1; i<n; i++) if(arr[i]>mx) mx=arr[i];
    printf("%d\n", mx);
    return 0;
}
