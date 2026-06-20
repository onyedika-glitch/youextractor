document.addEventListener('DOMContentLoaded', async () => {
    const extractBtn = document.getElementById('extractBtn');
    const statusEl = document.getElementById('status');
    const videoInfo = document.getElementById('video-info');
    const videoTitle = document.getElementById('video-title');

    async function getActiveTab() {
        const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
        return tab;
    }

    const tab = await getActiveTab();
    const APP_BASE = 'https://youextractor.me'; // Update if self-hosted

    if (tab.url && (tab.url.includes('youtube.com/watch') || tab.url.includes('youtu.be/'))) {
        extractBtn.innerHTML = `<span>🚀 Extract Code &amp; Guide</span>`;
        videoInfo.style.display = 'block';
        videoTitle.textContent = tab.title.replace(' - YouTube', '');

        // Subtle pulse animation on the button
        extractBtn.style.transition = 'transform 180ms ease, box-shadow 180ms ease';
        setTimeout(() => {
            if (extractBtn) extractBtn.style.boxShadow = '0 0 0 3px rgba(20,184,166,0.25)';
        }, 420);

        extractBtn.onclick = () => {
            extractBtn.disabled = true;
            extractBtn.style.opacity = '0.85';
            statusEl.textContent = 'Opening YouExtractor...';
            
            const targetUrl = `${APP_BASE}/?url=${encodeURIComponent(tab.url)}`;
            chrome.tabs.create({ url: targetUrl });
            
            // Close popup shortly after
            setTimeout(() => window.close(), 650);
        };

        statusEl.textContent = 'One click → full project + AI guide';
    } else {
        extractBtn.textContent = 'Go to YouTube ↗';
        extractBtn.disabled = false;
        extractBtn.onclick = () => {
            chrome.tabs.create({ url: 'https://youtube.com' });
            window.close();
        };
        statusEl.innerHTML = 'Open any coding tutorial on YouTube<br>to extract code instantly.';
    }
});
