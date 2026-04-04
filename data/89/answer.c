#include <stdio.h>
int main(){
    int n, cnt[7]={0}, x;
    scanf("%d", &n);
    for(int i=0; i<n; i++){ scanf("%d", &x); cnt[x]++; }
    for(int i=1; i<=6; i++){
        if(i>1) printf(" ");
        printf("%d", cnt[i]);
    }
    printf("\n");
    return 0;
}
