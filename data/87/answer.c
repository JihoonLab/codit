#include <stdio.h>
int main(){
    int n, arr[100], sum=0, cnt=0;
    scanf("%d", &n);
    for(int i=0; i<n; i++){ scanf("%d", &arr[i]); sum+=arr[i]; }
    int avg = sum/n;
    for(int i=0; i<n; i++) if(arr[i]>=avg) cnt++;
    printf("%d\n", cnt);
    return 0;
}
