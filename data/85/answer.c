#include <stdio.h>
int main(){
    char c;
    scanf("%c",&c);
    for(char i='a';i<=c;i++){
        if(i>'a') printf(" ");
        printf("%c",i);
    }
    return 0;
}
