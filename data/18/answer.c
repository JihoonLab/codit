#include <stdio.h>
int main()
{
    char s[20];
    scanf("%s", s);
    int i;
    for (i = 0; s[i]; i++) {
        if (s[i] != '-')
            printf("%c", s[i]);
    }
    return 0;
}
