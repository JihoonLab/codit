#include <stdio.h>
int main(){
    int n;
    scanf("%d",&n);
    for(int i=1;i<=n;i++){
        if(i>1) printf(" ");
        printf("%d",i);
    }
    return 0;
}
