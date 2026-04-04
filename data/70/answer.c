#include <stdio.h>
int main(){
    char a,b;
    scanf("%c %c",&a,&b);
    for(char c=a;c<=b;c++){
        if(c>a) printf(" ");
        printf("%c",c);
    }
    return 0;
}
