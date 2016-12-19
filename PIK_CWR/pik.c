#define _GNU_SOURCE
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/stat.h>
#include <unistd.h>

//-------functions--------//
int menu();
char* file_read(char *filename);
char* console_read();
void write_to_file(char* text, int operators);
int find_match(char *buff, char* word);
int operator_check(char *buff);
//-------functions--------//

//----------main---------//
int main(){
    char *result;
    char filename[30];
    int option;

    option = menu();

    switch(option){
        case 1:
            printf("Write the name of the input program:\n");
            scanf("%s",&filename);
            if(access(filename, F_OK ) != -1){
                result = file_read(filename);
                write_to_file("",operator_check(result));
            }else{
                printf("File not found!\n");
            }
        break;
        case 2:
            printf("Write the name of the program:\n");
            scanf("%s",&filename);
            if(access(filename, F_OK ) != -1){
                result = file_read(filename);
                printf("Operators found: %d\n",operator_check(result));
            }else{
                printf("File not found!\n");
            }

        break;
        case 3:
            result = console_read();
            operator_check(result);
           // printf("u entered: %s\n", result);
            write_to_file("",operator_check(result));
        break;
        case 4:
            result = console_read();
            printf("Operators found: %d\n",operator_check(result));
        break;

    }
    return 0;
}
//----------main---------//

int menu(){
    int option;

    printf("1. Read from file and save to file\n");
    printf("2. Read from file and print the result\n");
    printf("3. Read from console and save to file\n");
    printf("4. Read from console and print the result\n");
    printf("Select option and click enter:\n");
   // menu:
    scanf("%d", &option);
    if(option >= 1 && option <= 4){
        return option;
    }else{
        printf("You should select option between 1 and 4:\n");
       // goto menu;
    }
}

char* file_read(char *filename){
    FILE *fp;
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
        //exit(EXIT_FAILURE);
    }

    while (fgets(line_buff,file_status.st_size, fp)!=NULL){
        //printf("%s", line_buff);
        strcat(file_buff, line_buff);
     }

    free(line_buff);
    fclose(fp);
    return file_buff;
    free(file_buff);
}

void write_to_file(char* text, int operators){
    char filename[30];

    printf("Write the name of the output program:\n");
    scanf("%s",&filename);
    //strcat(filename,".c");

    FILE *fp = fopen(filename, "w");
    if (fp == NULL)
    {
        printf("Error opening file!\n");
        exit(1);
    }

    fprintf(fp, "Operators found: %d\nText:%s\n", operators, text);
    fclose(fp);
}

int find_match(char *buff, char* word){
    int j = 0;
    int i,counter=0;

    for(i = 0;i < strlen(buff);i++){
        if(buff[i] == word[j]){
            if(j == strlen(word)-1){
                counter++;
                j = 0;
                continue;
            }
        }else{
            j = 0;
            continue;
        }
        //printf("\nj = %d",j);
        j++;

    }
    return counter;
}

int operator_check(char *buff){
    int result;
    result = find_match(buff,"while");
    result+= find_match(buff,"for");
    return result;

}

char* console_read(){
    int i = 0;
    char c, *input;
    input = malloc(512 * sizeof(char *));
    if(input != NULL){
        while((c = getchar()) != EOF) {
            input[i++] = c;
            if (i >= 512){
              input = realloc(input, 1024 * sizeof(char *));
            }
        }
    }else{
        printf("ERROR!\n");
    }


    input[i] = '\0';
    return input;
}

