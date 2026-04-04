#include <stdio.h>
int main(){
    int n, arr[1000];
    scanf("%d", &n);
    for(int i=0; i<n; i++) scanf("%d", &arr[i]);
    for(int i=n-1; i>=0; i--){
        if(i<n-1) printf(" ");
        printf("%d", arr[i]);
    }
    printf("\n");
    return 0;
}
