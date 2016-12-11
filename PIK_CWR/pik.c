#define _GNU_SOURCE
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/stat.h>

char* file_read(){
    FILE *fp;
    char filename[] = "1.txt";
    struct stat file_status;
    char *line_buff = NULL;
    char *file_buff = NULL;

    //writes info about the file to file_status
    stat(filename, &file_status);
    //printf("%d\n", file_status.st_size);

    // file_status.st_size - the size of the file
    //dynamically allocate memory
    line_buff = (char*)malloc(file_status.st_size);
    file_buff = (char*)malloc(file_status.st_size);
    strcpy(file_buff, "");

    //opens the file
    fp = fopen(filename, "r");

    if (fp == NULL){
        printf("Error\n");
        exit(EXIT_FAILURE);
    }

    while (fgets(line_buff,file_status.st_size, fp)!=NULL){
        printf("%s", line_buff);
        strcat(file_buff, line_buff);
     }

    free(line_buff);
    fclose(fp);
    return file_buff;
    free(file_buff);
}
int operator_check(char *buff){
    int operators = 0;
    if((strstr(buff,"while") != NULL) || (strstr(buff,"for") != NULL) || (strstr(buff,"do") != NULL) ){
        operators++;
    }
    return operators;
}

int main(){
    char *result;
    int operators;
    result = file_read();
    operators = operator_check(result);
    printf("\nIt has %d operators\n",operators);
    return 0;
}
