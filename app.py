from flask import Flask
import sqlite3
import os

app = Flask(__name__)
DB_PATH = 'database.db'

def init_db():
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS counter (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            visits INTEGER
        )
    ''')
    cursor.execute('SELECT COUNT(*) FROM counter')
    if cursor.fetchone()[0] == 0:
        cursor.execute('INSERT INTO counter (visits) VALUES (0)')
    conn.commit()
    conn.close()

@app.route('/viikko2/')
def home():
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    cursor.execute('UPDATE counter SET visits = visits + 1')
    conn.commit()
    cursor.execute('SELECT visits FROM counter')
    visits = cursor.fetchone()[0]
    conn.close()

    return f"""
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Week 2 — visits and page refreshes in total</title>
        <style>
            body {{
                max-width: 800px;
                margin: 40px auto;
                padding: 0 20px;
                font-family: "Comic Sans MS", cursive, sans-serif;
                background-color: #FF4D4D;
                background-image: url('https://media.tenor.com/S61VCO73mOAAAAAj/linux-tux.gif');
                background-size: cover;
                background-attachment: fixed;
                color: #f7f7f7;
            }}
            h1 {{ color: white; }}
            code {{ color: #FFFFFF; background-color: rgba(0, 0, 0, 0.3); padding: 2px 4px; border-radius: 4px; }}
            .counter-box, .info-box {{ 
                background: rgba(216, 191, 216, 0.9); 
                color: #333333;
                padding: 20px; 
                border-radius: 8px; 
                margin-top: 20px; 
                box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            }}
            a {{ color: #005a8d; font-weight: bold; }}
        </style>
    </head>
    <body>
        <h1>Week 2</h1>
        <p>This software runs with nginx <code>/week2/</code>.</p>
        
        <div class="counter-box">
            <h3>Visitors</h3>
            <p>This page has been visited and refreshed in total: <strong>{visits}</strong> times.</p>
        </div>

        <div class="info-box">
            <h3>Storage, testing and GitHub</h3>
	    <h2>HOW EVERYTHING WORKS:</strong>
            <p><strong>Storage:</strong> Visitor counts are loaded in a local SQLite database same as the page refreshments to ensure the counter value persists on the server and application restarts.</p>
            <p><strong>Testing:</strong> It has been tested locally and on the remote server by refreshing the page in the browser many times, verifying that the database count increases on each page load!</p>
            <p><strong>GitHub source code:</strong> <a href="https://github.com/MikaelNorrbacka/linuxkurssiviikko2" target="_blank">GitHub Repository</a></p>
<img src="https://thumb.wikimedia.org/wikipedia/commons/thumb/c/c2/GitHub_Invertocat_Logo.svg/1280px-GitHub_Invertocat_Logo.svg.png?utm_source=en.wikipedia.org&utm_campaign=index&utm_content=thumbnail" alt="GitHub Logo" width="40" style="border-radius: 4px;">
        </div>

        <p style="margin-top: 20px;"><a href="/">&larr; Back to week 1 page</a></p>
    </body>
    </html>
    """

if __name__ == '__main__':
    init_db()
    app.run(host='127.0.0.1', port=5000)
