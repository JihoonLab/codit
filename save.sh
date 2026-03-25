#!/bin/bash
cd /home/judge

echo "변경사항 저장 중..."
git add -A
git commit -m "작업 저장: $(date '+%Y-%m-%d %H:%M:%S')"
git push origin main

if [ $? -eq 0 ]; then
    echo "저장 완료! GitHub에 푸시되었습니다."
else
    echo "푸시 실패. 네트워크를 확인해주세요."
fi
