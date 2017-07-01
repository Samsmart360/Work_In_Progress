#include <SPI.h>
#include <nRF24L01.h>
#include <RF24.h>
#include "DHT.h"

#define s 2
#define DHTPIN 2 
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);
RF24 radio(7, 8); // CNS,CE
void TO_GSM();

const byte address[][6] = {"000001","000002"};

byte i=0,j=0;

typedef struct{
  byte add[6];
  char stat;
}node;

node N[s];

void setup() {
 Serial.begin(9600);
 radio.begin();
 for(i=0;i<s;i++)
 {
  
 }
 for(i=0;i<s;i++)
 {
   for(j=0;j<6;j++)
   {
     N[i].add[j]=address[i][j];
     
   }
   N[i].stat='O';
 }
 i = 0;
 j = 0;
 
 radio.setPALevel(RF24_PA_MAX);
 radio.startListening();
}

void loop()
{
  Serial.println("\nHEY");
  unsigned long t = micros() ;
  bool timeout = false;
  switch(i)
   { case 0:Serial.println("\nOPEN pipe 0");
           radio.openReadingPipe(1,address[0]);
           
           break;
    case 1:Serial.println("OPEN pipe 1");
           radio.openReadingPipe(1,address[1]);
           
           break;
    case 2:TO_GSM();
           Serial.println("\nOPEN pipe 0");
           radio.openReadingPipe(1,address[0]);
           
           i=0;
           break;
   }
   delay(3000);
  while(!radio.available())
  {   if((micros()-t) > 20000)
       {timeout = true;
        break;}
  }
  if(timeout)
  {
    Serial.println("Timed out");
    N[i].stat='E';
    i++;
  }

  else
  {
    radio.read(&N[i],sizeof(node));
    i++;
  }
  delay(1000);
}


void TO_GSM()
{
  for(i=0;i<s;i++)
  {
     Serial.print("Node :");
     Serial.println(i+1);
     Serial.print("Road No:");
     for(j=0;j<6;j++)
     {Serial.print(N[i].add[j]-48);}
     Serial.print("\nStatus:");
     Serial.print(N[i].stat);
  }
}

