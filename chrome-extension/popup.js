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

    const isYoutubeWatch = tab.url && (tab.url.includes('youtube.com/watch') || tab.url.includes('youtu.be/') || /youtube\.com\/(shorts|embed|live)\//.test(tab.url));

    if (isYoutubeWatch) {
        extractBtn.innerHTML = `<span>⚡ Extract on this page</span>`;
        videoInfo.style.display = 'block';
        videoTitle.textContent = tab.title.replace(' - YouTube', '');

        extractBtn.onclick = async () => {
            extractBtn.disabled = true;
            extractBtn.style.opacity = '0.85';
            statusEl.textContent = 'Opening on this page…';

            try {
                await chrome.tabs.sendMessage(tab.id, { type: 'openPanel' });
            } catch (err) {
                statusEl.textContent = 'Reload the YouTube page, then click again.';
                extractBtn.disabled = false;
                extractBtn.style.opacity = '1';
                return;
            }
            window.close();
        };

        statusEl.textContent = 'Extract full project + AI guide — right on YouTube';
    } else if (tab.url && (tab.url.includes('youtube.com') || tab.url.includes('youtu.be'))) {
        extractBtn.textContent = 'Open a video first ↗';
        extractBtn.disabled = false;
        extractBtn.onclick = () => {
            chrome.tabs.create({ url: 'https://youtube.com' });
            window.close();
        };
        statusEl.innerHTML = 'Navigate to any YouTube video, then use the ⚡ Extract Code button on the page.';
    } else {
        extractBtn.textContent = 'Go to YouTube ↗';
        extractBtn.disabled = false;
        extractBtn.onclick = () => {
            chrome.tabs.create({ url: 'https://youtube.com' });
            window.close();
        };
        statusEl.innerHTML = 'Open any coding tutorial on YouTube — YouExtractor appears right on the video page.';
    }
});
