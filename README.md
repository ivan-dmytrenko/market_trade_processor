# Market Trade Processor (test)

At first, trade messages are validated. Then they are sent into  Resque worker, where them will be processed in background. The processed data will be used to make a chart. On frontend user can choose a pair of currencies and see number of messages over the world on the chart created before.
