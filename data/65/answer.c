#include <stdio.h>
int main(){
    int a,b;
    int cal[]={0,400,340,170,100,70};
    scanf("%d %d",&a,&b);
    if(cal[a]+cal[b]>500) printf("angry");
    else printf("no angry");
    return 0;
}
