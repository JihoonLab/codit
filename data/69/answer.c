#include <stdio.h>
int main(){
    int a,b;
    scanf("%d %d",&a,&b);
    int s=a<b?a:b, e=a<b?b:a;
    for(int i=s;i<=e;i++){
        if(i>s) printf(" ");
        printf("%d",i);
    }
    return 0;
}
