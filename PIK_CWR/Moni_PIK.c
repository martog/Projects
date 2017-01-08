#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int num_of_lines = 1;
char buff[1024];

void file_read(){
    FILE *f;
    char a, filename[30];
    char *ext;
    int i = 0;
    printf("Enter the name of input file:\n");
    scanf("%s",filename);
    ext = strrchr(filename, '.');
    ext+=1;
    //printf("extension: %s", ext);
    if(strcmp(ext,"c") == 0){
        f = fopen(filename, "r");
        while(fscanf(f, "%c", &a)!= EOF){
            buff[i] = a;
            if(a=='\n'){
                num_of_lines++;
            }
            i++;
        }
        //printf("The number of lines is: %d\n", num_of_lines);
    }else{
        printf("Error: You should enter .c file!");
    }
    fclose(f);
}

int num_of_comments(){
    int b = 0, comments = 0, scflag = 0, mcflag = 0;
    while(b <= strlen(buff)){
        if((buff[b] == '/')&&(buff[b+1] == '/')){
           scflag = 1;
        }
        if(scflag == 1 && (buff[b] == '\n' || buff[b] == '\0')){
            comments++;
            scflag = 0;
        }
        if(buff[b] == '/' && buff[b+1] == '*'){
            mcflag = 1;
        }
        if(mcflag == 1 && buff[b] == '*' && buff[b+1] == '/'){
            comments++;
            mcflag = 0;
        }

        b++;
    }
    //printf("The number of comments is %d\n", comments);
    return comments;
}

void read_from_console(){
    char c;
    int i = 0;
    while((c = getchar()) != EOF){
        buff[i] = c;
        if(c=='\n'){
                num_of_lines++;
            }
        i++;
    }
    //printf("The number of lines is: %d\n", num_of_lines);
}

void file_write(int num_of_lines, int comments){
    FILE *f;
    char filename[30];
    printf("Enter the name of the output file:\n");
    scanf("%s", &filename);
    f = fopen(filename, "w");
    fprintf(f,"The number of lines is: %d\n", num_of_lines);
    fprintf(f,"The number of comments is %d\n", comments);

}

int menu(){
    int option;
    printf("1. Read from file and save result to file\n");
    printf("2. Read from file and print result to console\n");
    printf("3. Read from console and save result to file\n");
    printf("4. Read from console and print result to console\n");
    printf("Select option:\n");
    scanf("%d", &option);

   return option;
}

int main(){
    int result;
    result = menu();
     switch(result){
        case 1:
            file_read();
            num_of_comments();
            file_write(num_of_lines, num_of_comments());
        break;
        case 2:
            file_read();
            printf("The number of lines is: %d\n", num_of_lines);
            printf("The number of comments is %d\n", num_of_comments());
        break;
        case 3:
            read_from_console();
            num_of_comments();
            file_write(num_of_lines, num_of_comments());
        break;
        case 4:
            read_from_console();
            printf("The number of lines is: %d\n", num_of_lines);
            printf("The number of comments is %d\n", num_of_comments());
        break;
        default:
            printf("\nError: Invalid option..\n");
    }
    return 0;
}
