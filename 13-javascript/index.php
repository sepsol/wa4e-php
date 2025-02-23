<script>
  a = '[empty]';
  var b = 'var';
  let c = 'let';
  const d = 'const';

  document.write("<h1>Hello from client!</h1>")
</script>
<noscript>
  Please turn on your JavaScript.
</noscript>

<?php echo "<h2>Hello from server!</h2>" ?>

<ul>
  <li>
    <a href="https://www.google.com" target="_blank">Google</a>
  </li>
  <li>
    <a href="https://www.bing.com" target="_blank" onclick="alert('Bingo!'); return false;">Bing</a>
  </li>
  <li>
    <a href="https://www.yahoo.com" target="_blank" onclick="event.preventDefault(); alert('Yahoo!');">Yahoo</a>
  </li>
  <li>
    <a href="https://www.github.com" target="_blank" onclick="(e) => e.preventDefault(); alert('GitHub!');">GitHub</a>
  </li>
  <li>
    <a href="https://duckduckgo.com" target="_blank" id="link">DuckDuckGo</a>
  </li>
</ul>

<script>
  const link = document.querySelector('#link');
  link.addEventListener('click', (e) => {
    e.preventDefault();
    alert('DuckDuckGo!');
  });
  console.log(link);
  console.dir(link);
</script>

<script type="text/javascript" src="script.js"></script>
