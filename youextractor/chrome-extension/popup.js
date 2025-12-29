document.addEventListener('DOMContentLoaded', async () => {
    const extractBtn = document.getElementById('extractBtn');
    const statusEl = document.getElementById('status');
    const videoInfo = document.getElementById('video-info');
    const videoTitle = document.getElementById('video-title');

    // Helper to get active tab
    async function getActiveTab() {
        const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
        return tab;
    }

    const tab = await getActiveTab();

    if (tab.url && (tab.url.includes('youtube.com/watch') || tab.url.includes('youtu.be/'))) {
        extractBtn.textContent = '🚀 Extract Code';
        videoInfo.style.display = 'block';
        videoTitle.textContent = tab.title.replace(' - YouTube', '');

        extractBtn.onclick = () => {
            const appUrl = 'https://youextractor.onrender.com';
            const targetUrl = `${appUrl}/?url=${encodeURIComponent(tab.url)}`;
            chrome.tabs.create({ url: targetUrl });
        };
    } else {
        extractBtn.textContent = 'Not a YouTube Video';
        extractBtn.disabled = true;
        statusEl.textContent = 'Please open a YouTube video to extract code.';
    }
});
