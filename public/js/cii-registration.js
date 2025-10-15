//-- public/js/cii-registration.js --//

// フォーム表示/非表示
function showInsuranceForm() {
  document.getElementById('insurance-form').style.display = 'block';
}

function hideInsuranceForm() {
  document.getElementById('insurance-form').style.display = 'none';
}

// 医療助成対象チェックボックスでフィールド有効/無効
function toggleMedicalAssistanceFields() {
  const checkbox = document.getElementById('medical_assistance_target');
  const fields = document.getElementById('medical-assistance-fields');
  if (checkbox.checked) {
    fields.style.display = 'block';
  } else {
    fields.style.display = 'none';
  }
}

// 保険者選択で詳細更新
function updateInsurerFields() {
  const select = document.getElementById('selected_insurer');
  const selectedOption = select.options[select.selectedIndex];
  const newInsurerNumber = document.getElementById('new_insurer_number');
  const newInsurerName = document.getElementById('new_insurer_name');
  const newPostalCode = document.getElementById('new_postal_code');
  const newAddress = document.getElementById('new_address');
  const newRecipientName = document.getElementById('new_recipient_name');

  if (select.value === '') {
    // 非選択の場合、入力フォームを有効化してクリア
    newInsurerNumber.disabled = false;
    newInsurerName.disabled = false;
    newPostalCode.disabled = false;
    newAddress.disabled = false;
    newRecipientName.disabled = false;
    newInsurerNumber.value = '';
    newInsurerName.value = '';
    newPostalCode.value = '';
    newAddress.value = '';
    newRecipientName.value = '';
  } else {
    // 選択されている場合、情報を表示して入力無効化
    newInsurerNumber.disabled = true;
    newInsurerName.disabled = true;
    newPostalCode.disabled = true;
    newAddress.disabled = true;
    newRecipientName.disabled = true;
    newInsurerNumber.value = selectedOption.getAttribute('data-number') || '';
    newInsurerName.value = selectedOption.getAttribute('data-name') || '';
    newPostalCode.value = selectedOption.getAttribute('data-postal') || '';
    newAddress.value = selectedOption.getAttribute('data-address') || '';
    newRecipientName.value = selectedOption.getAttribute('data-recipient') || '';
  }
}

// 新規登録郵便番号から住所を検索する関数
async function searchNewAddress() {
  const postalCode = document.getElementById('new_postal_code').value;
  const messageEl = document.getElementById('new-address-message');
  const addressEl = document.getElementById('new_address');

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
      addressEl.value = result.address1 + result.address2 + result.address3;
    } else {
      // 該当する住所が無い場合は空欄
      addressEl.value = '';
    }

  } catch (error) {
    console.error('New address search error:', error);
  }
}

// 新規登録郵便番号入力時の処理
document.getElementById('new_postal_code').addEventListener('input', function(e) {
  let value = this.value;

  // ハイフンを除去して数字のみに
  const numbersOnly = value.replace(/[^\d]/g, '');

  // 7桁になったら自動で住所検索を実行
  if (numbersOnly.length === 7) {
    // 表示用にハイフンを挿入（123-4567の形式）
    this.value = numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);

    // 自動で住所検索を実行
    searchNewAddress();
  } else if (numbersOnly.length > 7) {
    // 7桁を超える場合は7桁でカット
    const truncated = numbersOnly.substring(0, 7);
    this.value = truncated.substring(0, 3) + '-' + truncated.substring(3);
    searchNewAddress();
  } else if (numbersOnly.length <= 3) {
    // 3桁以下の場合はそのまま
    this.value = numbersOnly;
  } else {
    // 4-6桁の場合はハイフンを挿入
    this.value = numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);
  }
});
