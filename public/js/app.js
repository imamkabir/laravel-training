async function onUpload(e){
  e.preventDefault();
  const file = document.querySelector('#file').files[0];
  if(!file) return alert('Choose a file first');
  const btn = document.querySelector('#uploadBtn');
  btn.disabled = true; btn.textContent = 'Uploading...';
  const form = new FormData(); form.append('file', file);
  try {
    const res = await fetch('/api/upload', { method:'POST', body: form });
    const json = await res.json();
    const out = document.querySelector('#result');
    if(json.status === 'clean'){
      out.className = 'result good';
      out.innerHTML = `<strong>✅ Clean</strong> — saved as <code>${json.saved_name}</code><br><small class="muted">SHA256: ${json.sha256}</small>`;
    } else if(json.status === 'infected') {
      out.className = 'result bad';
      out.innerHTML = `<strong>⛔ Threat detected</strong> — ${json.message}<br><small class="muted">File quarantined.</small>`;
    } else {
      out.className = 'result';
      out.innerHTML = `<strong>⚠️ Error</strong> — ${json.error || 'Unknown'}`;
    }
  } catch(err){
    alert('Upload failed: '+err.message);
  } finally {
    btn.disabled = false; btn.textContent = 'Upload & Scan';
  }
}
document.addEventListener('DOMContentLoaded', ()=> {
  document.querySelector('#uploadForm').addEventListener('submit', onUpload);
});
