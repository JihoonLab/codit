#include <stdio.h>
int main(){
    int n,a[100];
    scanf("%d",&n);
    for(int i=0;i<n;i++) scanf("%d",&a[i]);
    for(int t=0;t<2;t++){
        for(int i=0;i<n;i++){
            if(t>0||i>0) printf(" ");
            printf("%d",a[i]);
        }
    }
    return 0;
}
