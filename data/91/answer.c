#include <stdio.h>
int main(){
    int n,sum=0,k=0;
    scanf("%d",&n);
    while(sum<n){
        k++;
        sum+=k;
    }
    printf("%d",k);
    return 0;
}
