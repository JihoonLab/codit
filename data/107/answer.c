#include <stdio.h>
int main(){
    int a,b;
    scanf("%d %d",&a,&b);
    for(int i=a;i<=b;i++){
        for(int j=1;j<=9;j++){
            if(!(i==a&&j==1)) printf(" ");
            printf("%d*%d=%d",i,j,i*j);
        }
    }
    return 0;
}
