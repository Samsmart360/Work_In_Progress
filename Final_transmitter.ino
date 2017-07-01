#include <SPI.h>
#include <nRF24L01.h>
#include <RF24.h>
#define br 9600
#define D2 A0
#define D3 A1
#define D4 A2

RF24 radio(7, 8); // CNS,CE

const byte address[6] = "000001";

typedef struct{
  byte add[6];
  char stat;
}node;

node N;
byte i,j;
long lvl[3];

void setup() {
 Serial.begin(br);
 for(i=0;i<6;i++)
 {
   N.add[i] = address[i];
 }
 N.stat = 'O';
 radio.begin();
 radio.openWritingPipe(address);
 radio.setPALevel(RF24_PA_MAX);
 radio.stopListening();
 pinMode(D2,INPUT);
 pinMode(D3,INPUT);
 pinMode(D4,INPUT);
  
}

void loop() {

N.stat = 'O';

  for(i=0;i<3;i++)
 {
  lvl[i] = 0;
 }
for(j=0;j<10;j++)
{
 for(i=0;i<3;i++)
 {
  lvl[i] = lvl[i] + analogRead(14+i);
  delay(5);
 }
}

if(lvl[0] > 9200)
{
   if(lvl[1] > 9200)
   {
    if(lvl[2] > 9200)
    {
      N.stat = 'H';
    }
    else
    { N.stat = 'M';}
   }
   else
   {N.stat = 'L';}
}
  radio.write(&N,sizeof(N));
  delay(10);
}
