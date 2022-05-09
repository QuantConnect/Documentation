# Import from https://www.quantconnect.com/docs/v2/research-environment/object-store/storing-data

<br><br>-What is gathering keys:
<br>-Gathering all of the keys stored in the ObjectStore key-value dictionary
<br>-Why gather keys:
<br>&nbsp;&nbsp;&nbsp;&nbsp;- So you can see what is available in the ObjectStore
<br>&nbsp;&nbsp;&nbsp;&nbsp;- So you don't over-write a key-value pair in the OS
<br>-How(?):
 <br>&nbsp;&nbsp;&nbsp;&nbsp;-can be gathered by using the GetEnumerator method
<br>         keys = [str(j).split(',')[0][1:] for _, j in enumerate(self.ObjectStore.GetEnumerator())]