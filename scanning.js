// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', async () => {
    const scanButton = document.getElementById('start-scan');
    const resultDiv = document.getElementById('result');
    
    scanButton.addEventListener('click', handleScanClick);
    
    // Preload the SDK when page loads
    try {
        resultDiv.textContent = 'Loading scanner...';
        await loadBlinkIdSDK();
        resultDiv.textContent = 'Scanner ready. Click "Scan Passport" to begin.';
        scanButton.disabled = false;
    } catch (error) {
        resultDiv.innerHTML = `<span style="color:red">Error: ${error.message}</span>`;
        console.error('SDK loading failed:', error);
    }
});

async function handleScanClick() {
    const resultDiv = document.getElementById('result');
    
    try {
        // Verify SDK loaded
        if (typeof BlinkID === 'undefined') {
            throw new Error('Scanner engine not available');
        }

        resultDiv.textContent = 'Initializing...';
        
        const licenseKey = "sRwCAAlsb2NhbGhvc3QGbGV5SkRjbVZoZEdWa1QyNGlPakUzTkRjMk9EUTVNemsxTWpVc0lrTnlaV0YwWldSR2IzSWlPaUl3WmpZeU1XUXdOeTAyTlROaUxUUmtPVEF0T0dReE15MWtORGRrTVdNNU1EUXhaVGNpZlE9PQvKb8F/wKTvsNZxw3LNlfQGWGPiHjPVe/UwdRnIh7nYc6qjZfhHXHMaExDdPpIygXvNtm1Dx1Wc2f7ENhluEQeEQZOhVsLk+kf48OmGx7lJ5hbAPsL8LrKFZn1Xwl9hg4BtdbEUK37fyBhKZAPwgHn5oWc=";
        const loadSettings = new BlinkID.SDK.LoadSettings(licenseKey);
        
        // Load WASM module with progress feedback
        resultDiv.textContent = 'Loading scanner components (1/3)...';
        const sdk = await BlinkID.SDK.loadWasmModule(loadSettings);
        
        resultDiv.textContent = 'Preparing recognition (2/3)...';
        const recognizer = await BlinkID.SDK.createBlinkIdRecognizer(sdk);
        const recognizerRunner = await BlinkID.SDK.createRecognizerRunner(sdk, [recognizer], false);
        
        resultDiv.textContent = 'Starting camera (3/3)...';
        const videoRecognizer = await BlinkID.SDK.VideoRecognizer.createVideoRecognizerFromCameraStream(
            sdk,
            resultDiv
        );
        
        resultDiv.textContent = 'Scan your passport now...';
        const processResult = await videoRecognizer.recognize(recognizerRunner);
        
        if (processResult !== BlinkID.SDK.RecognizerResultState.Empty) {
            const result = await recognizer.getResult();
            displayAndStoreResults(result);
        } else {
            resultDiv.textContent = 'No passport detected. Please try again.';
        }
        
        // Clean up
        await Promise.allSettled([
            recognizerRunner.delete(),
            recognizer.delete(),
            sdk.delete()
        ]);
        
    } catch (error) {
        resultDiv.innerHTML = `<span style="color:red">Error: ${error.message}</span>`;
        console.error('Scanning error:', error);
    }
}

async function displayAndStoreResults(result) {
    const resultDiv = document.getElementById('result');
    
    try {
        const passportData = {
            fullName: result.fullName || "N/A",
            gender: result.sex || "N/A",
            nationality: result.nationality || "N/A",
            dob: result.dateOfBirth?.originalString || "N/A",
            passportNumber: result.documentNumber || "N/A",
            passportImage: result.faceImage ? await blobToBase64(result.faceImage) : null
        };
        
        // Display results immediately
        resultDiv.innerHTML = `
            <h3>Scan Successful!</h3>
            <p><strong>Name:</strong> ${passportData.fullName}</p>
            <p><strong>Passport No:</strong> ${passportData.passportNumber}</p>
            <p>Saving data...</p>
        `;
        
        // Store in database
        await sendToDatabase(passportData);
        resultDiv.innerHTML += `<p style="color:green">Data saved successfully!</p>`;
        
    } catch (error) {
        resultDiv.innerHTML += `<p style="color:red">Storage error: ${error.message}</p>`;
        throw error;
    }
}

// Helper functions remain the same
async function blobToBase64(blob) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result.split(',')[1]);
        reader.onerror = reject;
        reader.readAsDataURL(blob);
    });
}

async function sendToDatabase(data) {
    const response = await fetch("register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    });
    
    if (!response.ok) {
        throw new Error(`Server error: ${response.status}`);
    }
    
    return await response.json();
}