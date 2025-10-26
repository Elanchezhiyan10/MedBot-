<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MedBot</title>
  <link rel="stylesheet" href="cbt.css">
  <style>
    /* Hamburger button */
    .hamburger-btn { cursor: pointer; padding: 5px; }
    .hamburger-btn span { display: block; width: 25px; height: 3px; background: black; margin: 4px 0; }
    
    /* Dropdown menu */
    .dropdown { position: absolute; right: 0; top: 35px; width: 140px; background: white; border: 1px solid #ccc; border-radius: 5px; display: none; z-index: 100; }
    .dropdown ul { list-style: none; padding: 0; margin: 0; }
    .dropdown li { padding: 8px 12px; cursor: pointer; }
    .dropdown li:hover { background-color: #f0f0f0; }

    /* Top bar */
    .top-bar { display: flex; justify-content: space-between; align-items: center; position: relative; margin-bottom:10px; }
    .dropdown li {
  padding: 10px 14px;
  cursor: pointer;
  border-bottom: 1px solid #eee;
}
    .dropdown li:last-child {
  border-bottom: none;
  border-radius: 0 0 8px 8px;
}
    /* About box */
    #aboutBox { display:none; border:1px solid #ccc; padding:10px; margin-bottom:10px; border-radius:5px; background:#f9f9f9; }
  </style>
</head>
<body>
  <div class="chat-container">
    <div class="top-bar">
      <h2>MedBot</h2>
      <!-- Hamburger Menu -->
      <div class="relative">
        <div class="hamburger-btn" id="hamburgerBtn">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <div class="dropdown" id="dropdownMenu">
          <ul>
            <li onclick="showAbout()">About</li>
            <li onclick="alert('Rate MedBot feature coming soon')">Rate</li>
            <li onclick="window.location.href='login.html'">Logout</li>
            <li onclick="resetChat()">New Chat</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- About Box -->
    <div id="aboutBox">
      <strong>MedBot</strong>
      <p>MedBot is an AI-powered medical assistant that helps you analyze symptoms and provide health-related suggestions. Simply type your symptoms and get instant guidance!</p>
      <button onclick="closeAbout()" style="margin-top:5px; padding:3px 6px;">Close</button>
    </div>

    <div class="chat-box" id="chat-box"></div>
    <div class="input-area">
      <input type="text" id="user-input" placeholder="Type your symptoms...">
      <button id="send">Send</button>
    </div>
  </div>

  <script>
    const chatBox = document.getElementById('chat-box');
    const userInput = document.getElementById('user-input');
    const sendButton = document.getElementById('send');

    sendButton.addEventListener('click', sendMessage);
    userInput.addEventListener('keypress', function (e) {
      if (e.key === 'Enter') sendMessage();
    });

    function appendMessage(sender, text) {
      const div = document.createElement('div');
      div.classList.add(sender);
      div.textContent = text;
      chatBox.appendChild(div);
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    function sendMessage() {
      const message = userInput.value.trim();
      if (message === '') return;

      appendMessage('user', 'You: ' + message);
      userInput.value = '';

      fetch('chatbot_back.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'message=' + encodeURIComponent(message)
      })
      .then(response => response.text())
      .then(data => appendMessage('bot', 'MedBot: ' + data))
      .catch(() => appendMessage('bot', '⚠ Connection error'));
    }

    // Hamburger menu toggle
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    hamburgerBtn.addEventListener('click', () => {
      dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    function resetChat() {
      chatBox.innerHTML = '';
      dropdownMenu.style.display = 'none';
    }

    function showAbout() {
      document.getElementById('aboutBox').style.display = 'block';
      dropdownMenu.style.display = 'none';
    }

    function closeAbout() {
      document.getElementById('aboutBox').style.display = 'none';
    }

    // Optional: Close dropdown if clicked outside
    document.addEventListener('click', function(event) {
      if (!hamburgerBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none';
      }
    });
  </script>
</body>
</html>
