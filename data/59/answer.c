#include <stdio.h>
int main(){
    int n;
    scanf("%d",&n);
    if((n>=50&&n<=70)||(n%6==0)) printf("win");
    else printf("lose");
    return 0;
}
