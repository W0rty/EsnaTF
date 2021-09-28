FROM openjdk:latest

ARG user_script_name
ARG user_script_name_without_java
ARG exo_name
ARG test_script_name
ARG test_script_name_without_java

ENV PROG_NAME=${test_script_name_without_java}

RUN groupadd bootcamp
RUN useradd -g bootcamp --home=/home/bootcamp bootcamp

WORKDIR /home/bootcamp/
COPY ${user_script_name} .
COPY ./${exo_name}/${test_script_name} .

RUN javac ${user_script_name}
RUN javac ${test_script_name}

CMD java $PROG_NAME