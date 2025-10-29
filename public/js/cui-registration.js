//-- public/js/cui-registration.js --//


// 郵便番号から住所を検索する関数
async function searchAddress() {
  const postalCode = document.getElementById('postal_code').value;
  const messageEl = document.getElementById('address-message');
  const address1El = document.getElementById('address_1');
  const address2El = document.getElementById('address_2');

  // 郵便番号を数字のみに変換
  const cleanPostalCode = postalCode.replace(/[^\d]/g, '');

  // 7桁の数字かチェック
  if (cleanPostalCode.length !== 7) {
  return; // エラーメッセージは表示せず、単に終了
  }

  try {
  // 郵便番号APIを呼び出し（zipcloud API使用）
  const response = await fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${cleanPostalCode}`);
  const data = await response.json();

  if (data.status === 200 && data.results && data.results.length > 0) {
    const result = data.results[0];
    
    // 都道府県と市区町村を設定
    address1El.value = result.address1;
    address2El.value = result.address2 + result.address3;
  } else {
    // 該当する住所が無い場合は空欄
    address1El.value = '';
    address2El.value = '';
  }

  } catch (error) {
  showMessage('住所検索でエラーが発生しました。', 'error');
  console.error('Address search error:', error);
  }
}

// メッセージ表示関数
function showMessage(message, type) {
  const messageEl = document.getElementById('address-message');
  messageEl.textContent = message;
  messageEl.className = type;
  messageEl.style.display = 'block';

  // エラーメッセージは5秒後に消す
  if (type === 'error') {
  setTimeout(() => {
    messageEl.style.display = 'none';
  }, 5000);
  }
}

// 郵便番号入力時の処理
document.getElementById('postal_code').addEventListener('input', function(e) {
  let value = this.value;
  
  // ハイフンを除去して数字のみに
  const numbersOnly = value.replace(/[^\d]/g, '');
  
  // 7桁になったら自動で住所検索を実行
  if (numbersOnly.length === 7) {
  // 表示用にハイフンを挿入（123-4567の形式）
  this.value = numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);
  
  // 自動で住所検索を実行
  searchAddress();
  } else if (numbersOnly.length > 7) {
  // 7桁を超える場合は7桁でカット
  const truncated = numbersOnly.substring(0, 7);
  this.value = truncated.substring(0, 3) + '-' + truncated.substring(3);
  searchAddress();
  } else if (numbersOnly.length <= 3) {
  // 3桁以下の場合はそのまま
  this.value = numbersOnly;
  } else {
  // 4-6桁の場合はハイフンを挿入
  this.value = numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);
  }
});
