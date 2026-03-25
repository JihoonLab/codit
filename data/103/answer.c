#include <stdio.h>
int main(){
    char c;
    while((c=getchar())!=EOF && c!='\n'){
        if(c!=' ') putchar(c);
    }
    return 0;
}
